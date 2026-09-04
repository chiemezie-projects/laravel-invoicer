@php
$existingItems = old('items', isset($invoice->items) && $invoice->items->count() ? $invoice->items->map(fn($i)=>['description'=>$i->description,'quantity'=>$i->quantity,'unit_price'=>$i->unit_price])->toArray() : [['description'=>'','quantity'=>1,'unit_price'=>'']]);
@endphp

<div class="grid sm:grid-cols-2 gap-4 mb-6">
    <label class="block">
        <span class="text-sm font-medium">Invoice Number *</span>
        <input name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm mono">
    </label>
    <label class="block">
        <span class="text-sm font-medium">Client *</span>
        <select name="client_id" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white">
            <option value="">— Select client —</option>
            @foreach($clients as $c)
                <option value="{{ $c->id }}" @selected(old('client_id', $invoice->client_id)==$c->id)>{{ $c->name }} @if($c->company) ({{ $c->company }}) @endif</option>
            @endforeach
        </select>
        @if($clients->isEmpty())
            <p class="text-xs text-amber-600 mt-1">No clients yet — <a href="{{ route('clients.create') }}" class="underline">create one</a></p>
        @endif
    </label>
    <label class="block">
        <span class="text-sm font-medium">Status</span>
        <select name="status" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white">
            @foreach(['draft','sent','paid','overdue','void'] as $s)
                <option value="{{ $s }}" @selected(old('status',$invoice->status)===$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm font-medium">Currency</span>
        <input name="currency" value="{{ old('currency', $invoice->currency ?? 'USD') }}" required maxlength="3" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm mono uppercase">
    </label>
    <label class="block">
        <span class="text-sm font-medium">Issue Date *</span>
        <input type="date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d') : '') }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </label>
    <label class="block">
        <span class="text-sm font-medium">Due Date *</span>
        <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '') }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </label>
    <label class="block">
        <span class="text-sm font-medium">Tax Rate %</span>
        <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </label>
    <label class="block">
        <span class="text-sm font-medium">Discount (flat)</span>
        <input type="number" step="0.01" min="0" name="discount" value="{{ old('discount', $invoice->discount) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </label>
    <label class="block sm:col-span-2">
        <span class="text-sm font-medium">Notes</span>
        <textarea name="notes" rows="2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Payment terms, thank you note...">{{ old('notes', $invoice->notes) }}</textarea>
    </label>
</div>

<div class="border-t border-gray-200 pt-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm">Line Items</h3>
        <button type="button" onclick="addRow()" class="text-xs bg-gray-900 text-white px-3 py-1.5 rounded-lg"><i class="ri-add-line"></i> Add line</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-gray-500 uppercase">
                <tr>
                    <th class="text-left font-medium pb-2 w-[50%]">Description</th>
                    <th class="text-right font-medium pb-2">Qty</th>
                    <th class="text-right font-medium pb-2">Unit Price</th>
                    <th class="text-right font-medium pb-2">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="items-body">
                @foreach($existingItems as $idx => $it)
                <tr class="item-row">
                    <td class="pr-2 py-1"><input name="items[{{ $idx }}][description]" value="{{ $it['description'] }}" required placeholder="Design work" class="w-full rounded-lg border border-gray-300 px-2 py-1.5 text-sm"></td>
                    <td class="pr-2 py-1"><input type="number" step="0.01" min="0.01" name="items[{{ $idx }}][quantity]" value="{{ $it['quantity'] }}" required class="qty w-20 rounded-lg border border-gray-300 px-2 py-1.5 text-sm text-right"></td>
                    <td class="pr-2 py-1"><input type="number" step="0.01" min="0" name="items[{{ $idx }}][unit_price]" value="{{ $it['unit_price'] }}" required class="price w-24 rounded-lg border border-gray-300 px-2 py-1.5 text-sm text-right"></td>
                    <td class="text-right py-1 mono text-sm line-total">$0.00</td>
                    <td class="pl-1"><button type="button" onclick="this.closest('tr').remove();calc()" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-red-50 text-red-500"><i class="ri-close-line"></i></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 text-right text-xs text-gray-500">Tip: add at least one line item</div>
</div>

<script>
let idx = {{ count($existingItems) }};
function addRow(){
    const tbody=document.getElementById('items-body');
    const tr=document.createElement('tr');
    tr.className='item-row';
    tr.innerHTML=`<td class="pr-2 py-1"><input name="items[${idx}][description]" required placeholder="New item" class="w-full rounded-lg border border-gray-300 px-2 py-1.5 text-sm"></td>
    <td class="pr-2 py-1"><input type="number" step="0.01" min="0.01" name="items[${idx}][quantity]" value="1" required class="qty w-20 rounded-lg border border-gray-300 px-2 py-1.5 text-sm text-right"></td>
    <td class="pr-2 py-1"><input type="number" step="0.01" min="0" name="items[${idx}][unit_price]" value="0" required class="price w-24 rounded-lg border border-gray-300 px-2 py-1.5 text-sm text-right"></td>
    <td class="text-right py-1 mono text-sm line-total">$0.00</td>
    <td class="pl-1"><button type="button" onclick="this.closest('tr').remove();calc()" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-red-50 text-red-500"><i class="ri-close-line"></i></button></td>`;
    tbody.appendChild(tr); idx++; bindCalc();
}
function calc(){
    document.querySelectorAll('.item-row').forEach(r=>{
        const q=parseFloat(r.querySelector('.qty')?.value||0);
        const p=parseFloat(r.querySelector('.price')?.value||0);
        r.querySelector('.line-total').textContent='$'+(q*p).toFixed(2);
    });
}
function bindCalc(){
    document.querySelectorAll('.qty,.price').forEach(el=>el.addEventListener('input',calc));
}
bindCalc(); calc();
</script>
