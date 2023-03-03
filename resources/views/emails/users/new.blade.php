@component('mail::message')
    # New User

   {{$user->name}} just registered. Please assign the user a role.
@component('mail::button', ['url' => config('app.url'). '#/users/' . $user->id])
Assign Role
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
