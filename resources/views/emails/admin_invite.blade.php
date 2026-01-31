<h3>Hello {{ $admin->name }},</h3>

<p>You have been added as an Admin for {{ $admin->company->name }}.</p>

<p>Your login credentials:</p>
<ul>
    <li>Email: {{ $admin->email }}</li>
    <li>Password: {{ $password }}</li>
</ul>

{{-- <p>Please login and change your password immediately.</p> --}}
