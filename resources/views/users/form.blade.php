<div>
    <label class="mb-1 block text-sm font-medium">Nama</label><input class="form-input" name="nama"
        value="{{ old('nama', $user?->nama) }}" required><x-field-error field="nama" />
</div>
<div><label class="mb-1 block text-sm font-medium">Username</label><input class="form-input" name="username"
        value="{{ old('username', $user?->username) }}" required><x-field-error field="username" /></div>
<div><label class="mb-1 block text-sm font-medium">Email</label><input class="form-input" type="email" name="email"
        value="{{ old('email', $user?->email) }}"><x-field-error field="email" /></div>
<div><label class="mb-1 block text-sm font-medium">Password</label><input class="form-input" type="password"
        name="password" {{ $user ? '' : 'required' }}><x-field-error field="password" /></div>
<div><label class="mb-1 block text-sm font-medium">Role</label><select class="form-input" name="role" required>
        @foreach (['manajer', 'admin', 'staff_workshop'] as $role)
            <option value="{{ $role }}" @selected(old('role', $user?->role) === $role)>{{ $role }}</option>
        @endforeach
    </select><x-field-error field="role" /></div>
<div><label class="mb-1 block text-sm font-medium">No. HP</label><input class="form-input" name="no_hp"
        value="{{ old('no_hp', $user?->no_hp) }}"><x-field-error field="no_hp" /></div>
<div><label class="mb-1 block text-sm font-medium">Foto</label><input type="file" name="foto" accept="image/*">
</div>
