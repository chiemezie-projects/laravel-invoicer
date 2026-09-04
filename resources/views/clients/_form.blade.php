<div class="grid sm:grid-cols-2 gap-4">
    <label class="block">
        <span class="text-sm font-medium text-gray-700">Name *</span>
        <input name="name" value="{{ old('name', $client->name) }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Acme Co contact">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-gray-700">Company</span>
        <input name="company" value="{{ old('company', $client->company) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Acme LLC">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-gray-700">Email</span>
        <input type="email" name="email" value="{{ old('email', $client->email) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="billing@acme.com">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-gray-700">Phone</span>
        <input name="phone" value="{{ old('phone', $client->phone) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="+1 555...">
    </label>
    <label class="block sm:col-span-2">
        <span class="text-sm font-medium text-gray-700">Address</span>
        <input name="address" value="{{ old('address', $client->address) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="123 Main St">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-gray-700">City</span>
        <input name="city" value="{{ old('city', $client->city) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-gray-700">VAT Number</span>
        <input name="vat_number" value="{{ old('vat_number', $client->vat_number) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm mono" placeholder="GB...">
    </label>
</div>
