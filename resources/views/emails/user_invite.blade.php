<h3>Hello {{ $user->name }},</h3>

<p>You have been invited as <strong>{{ strtolower($user->role) }}</strong>
   to {{ $user->company->name }}.</p>

<p>Your login details:</p>
<ul>
    <li>Email: {{ $user->email }}</li>
    <li>Password: {{ $password }}</li>
</ul>

{{-- <p>Please login and change your password.</p> --}}
