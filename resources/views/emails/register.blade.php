@component('mail::message')
# Your Registration is almost complete!

Click on the link below to confirm your registration:

@component('mail::button', ['url' => 'https://petsapp-a1393.web.app//verificationUser?t='.$token])
Click here to confirm the registration
@endcomponent

Thanks,<br>
 WoW Petius 
@endcomponent
