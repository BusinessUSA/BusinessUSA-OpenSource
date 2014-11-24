Push Notifications Overview
===========================

The Push Notifications module provides the feature set to send out push
notifications to iOS (iPhone/iPad) and Android devices using Apple's
Push Notification Service (APNS) as well as Google's Android Cloud to Device
Messaging framework (C2DM) and or Google's Cloud Messaging for Android (GCM).
This module does not rely on any external services and allows site owners to
send out push notifications to any mobile device for free.



Push Notification Settings
==========================
Navigate to /admin/config/services/push_notifications/configure to configure the
push notification settings for APNS, GCM or C2DM.



Mass-Push notification
======================
Navigate to admin/config/services/push_notifications/message. On this page,
you can compose a push notification to send out to all registered recipients.
Enter your message, select the recipient groups (iOS, Android or both) and
click on "Send Push Message".



Apple Push Notification Service (APNS)
======================================

APNS Certificates
-----------------
APNS requires a certificate file. Apple distinguishes between development
certificates (used for development versions of your iOS app) and production
certificates (used when your iOS app is available in the app store). An
incorrectly generated or missing certificate will prevent you from sending
any push notifications.

How to generate your certificate
--------------------------------
The generation for either certificate is explained in this tutorial
(http://blog.boxedice.com/2009/07/10/how-to-build-an-apple-push-notification-provider-server-tutorial/),
here is a summary of the essential steps:
1. Log in to the iPhone Developer Connection Portal and click App IDs
2. Ensure you have created an App ID without a wildcard. Wildcard IDs cannot use
the push notification service. For example, our iPhone application ID looks something
like AB123346CD.com.serverdensity.iphone
3. Click Configure next to your App ID and then click the button to generate a
Push Notification certificate. A wizard will appear guiding you through the
steps to generate a signing authority and then upload it to the portal, then
download the newly generated certificate. This step is also covered in the Apple documentation.
4. Import your aps_developer_identity.cer into your Keychain by double clicking
the .cer file.
5. Launch Keychain Assistant from your local Mac and from the login keychain,
filter by the Certificates category. You will see an expandable option called
“Apple Development Push Services”
6. Expand this option then right click on “Apple Development Push Services” >
Export “Apple Development Push Services ID123″. Save this as apns-dev-cert.p12
file somewhere you can access it.
7. Do the same again for the “Private Key” that was revealed when you expanded
“Apple Development Push Services” ensuring you save it as apns-dev-key.p12 file.
8. These files now need to be converted to the PEM format by executing this command
from the terminal:
  1. openssl pkcs12 -clcerts -nokeys -out apns-dev-cert.pem -in apns-dev-cert.p12
  2. openssl pkcs12 -nocerts -out apns-dev-key.pem -in apns-dev-key.p12
9. If you wish to remove the passphrase, either do not set one when exporting/
converting or execute:
  openssl rsa -in apns-dev-key.pem -out apns-dev-key-noenc.pem
10. Finally, you need to combine the key and cert files into a apns-dev.pem
file we will use when connecting to APNS:
  cat apns-dev-cert.pem apns-dev-key-noenc.pem > apns-dev.pem
11. Rename this file to 'apns-development-[randomstring].pem' for the development certificate
and 'apns-production-[randomstring].pem' for the production certificate and copy the files into
the 'certificates' folder where you installed this module

How to name your certificates files
-----------------------------------
The filename for the development certificate should be
'apns-development-[randomstring].pem', the filename for the  production certificate
should be 'apns-production-[randomstring].pem'. You can find the exact filename on
the Push Notification module configuration page (admin/config/services/push_notifications/configure)
under "APPLE PUSH NOTIFICATIONS".

Where to put certificate files
------------------------------
For best security, place the certificates into a folder outside of your webroot,
i.e. a directory that's not accessible through the Internet. Set the path for
your certificates on the Push Notification module configuration page
(admin/config/services/push_notifications/configure) under
"APNS Certificate Folder Path".

Alternatively, you can put the certificates into the 'certificates' folder
of the push notification module. In this case, leave the "APNS Certificate Folder Path"
field on the Push Notification module configuration page blank.



Google Cloud Messaging
======================
GCM requires you to create an API key.
See http://developer.android.com/guide/google/gcm/index.html for further instructions.



REST Interface
==========================
Mobile apps can register the device by calling the REST interface provided by
the service module. Don't forget to enable the push_notifications resource.
This module adds a permission called 'register device token'. Only roles with
this permission may register a device token through a service.
This module should work with both anonymous and authenticated users.

This service requires 2 arguments
- 'token' (string): The iOS device token (which is not the same as the iOS UDID) or the Android registration id
- 'type' (string): Pass 'ios' for ios device tokens and 'android' for android registration ids
- Return arguments
  If successful, the service returns an array with two keys:
  - 'success', value is 1
  - 'message', value is either 'This token is already registered to this user.'
     if the token was already registered or 'This token was successfully stored in
     the database.' for new tokens

---Register
URL: http://my-drupal-installation/services_module_endpoint/push_notifications
Method: "POST"
Payload: token={token}&type={type}

---Unregister
http://my-drupal-installation/services_module_endpoint/push_notifications/{token}
Method: "DELETE"
Payload:
