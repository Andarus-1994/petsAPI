@component('mail::message')
# Your Registration is almost complete!

Click on the link below to confirm your registration:

@component('mail::button', ['url' => 'http://127.0.0.1:3000/verificationUser?t='.$token])
Click here to confirm the registration
@endcomponent

Thanks,<br>
 WoW Petius 
@endcomponent
