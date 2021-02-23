=== Generate PDF using Contact Form 7 ===
Contributors: zealopensource
Donate link: http://www.zealousweb.com/payment/
Tags: contact corm 7, contact form, contact, PDF, email, form, PDF mail
Requires at least: 3.0.1
Requires PHP: 5.6
Tested up to: 5.5.3
Stable tag: 1.9.7
Version: 1.9.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate PDF using Contact Form 7 Plugin provides an easier way to download document files in PDF format, open PDF document file or send as it as an attachment after the successful form submission.

== Description ==

Generate PDF using Contact Form 7 plugin provides an easier way to download PDF documents, open the PDF document file after the successful form submission.

Here, a user can set the document file (PDF) from the ‘Form Setting’ Page of each Contact form.

When the user fills the form and submits it, the same document will get open in a new tab. Once it gets opened, the user would be able to download it to the local system.

Also, in case an admin does not want any user to open it in-browser, admin can adjust settings and send that particular PDF as an email attachment. 

<strong>[Demo for Generate PDF using Contact Form 7](http://demo.zealousweb.com/wordpress-plugins/generate-pdf-using-contact-form-7/)</strong>

**Note**
For PDF we have used MPDF library so in admin side it's support Below HTML and CSS with editor to generate PDF.
https://mpdf.github.io/css-stylesheets/supported-css.html
https://mpdf.github.io/html-support/html-tags.html

== Features ==

* Attach PDF file to the Form Notifications Emails that are sent to the user and/or administrator, from the Admin side.
* In the message, the link of the attached PDF file is displayed, along with Thank You Message of the Form Submission
* Admin can add different PDFs with different Contact Forms and can create multiple forms.
* Admin can create their own PDF with submitted Data in the Form
* Admin can customize your PDF form by adding a logo on the Header and other relevant Form * Fields while sending a Thank You Message to the user.
* Ablity to Update PDF Header/Footer Text.
* Ability to add file option with our PDF attachement in mail.
* We can use Page Break and new content will be move on next pages in PDF.

== Frequently Asked Questions ==

= Can I set different types of files instead of PDF files? =

No, only PDF file Generated.

= Can I set the custom body of PDF? = 

Yes,you can customize the PDF body as per your choice from the HTML editor.

= Is the PDF file sent as an attachment to the user’s mail? =

Yes, you can use mail 2 option from contact form 7

= Is there a need to increase WP Upload size for this Plugin? =

Yes, there is a need to increase WP Upload size, the maximum size allowed is up to 10M.

= Why PDF not generated? =

Please make sure plugin-name/attachments folder have read/write permission.

== Installation ==

1. Download the plugin zip file from WordPress.org plugin site to your desktop / PC
2. If the file is downloaded as a zip archive, extract the plugin folder to your desktop.
3. With your FTP program, upload the plugin folder to the wp-content/plugins folder in your WordPress directory online
4. Go to the Plugin screen and find the newly uploaded Plugin in the list.
5. Click ‘Activate Plugin’ to activate it.

== Screenshots ==
1. Screenshot 'screenshot-1.png' Shows PDF settings of upload PDF in the Contact form.
2. Screenshot 'screenshot-2.png' PDF settings of Customize PDF in the Contact form.


== Changelog ==

= 1.9.7 =
* Fix - Solved issue of Attachement conflict of Default CF7 and our PDF with emails.

= 1.9.6 =
* Add - Add Pages Break Feature for move content to the next Pages.

= 1.9.5 =
* Fix - Fixed Attachment issue with save attachment into Database.

= 1.9.4 =
* Fix - Fixed Attachment issue.

= 1.9.3 =
* Add - Add New option to Set Logo Size in Generated PDF.

= 1.9.2 =
* Fix - Fixed MPDF library Errro with update latest one.

= 1.9.1 =
* Add - Add New Feature of setting margin of Create PDF with Data.

= 1.9 =
* Add - Add Date format features and match with WP general settings. 

= 1.8 =
* Add - Fixed Issue. 

= 1.7 =
* Add - Now plugin support with Contact Form 7 file option with our PDF attachment. 

= 1.6 =
* Fix - Issue fixed regarding tooltip with latest version of WordPress 5.5.

= 1.5 =
* Fix - Now plugin support in version 5.2 and less then 5.2 of Contact Form 7.

= 1.4 =
* Add - Set Default Font to FreeSans for PDF file.

= 1.3 =
* Fix - We have fixed for support Dropdown and Radio button in PDF generate.

= 1.2 =
* Add - Add New Feature to edit PDF Header/Footer Text.

= 1.1 =
* Add support Link.

= 1.0 =
* Release version.

== Upgrade Notice ==

= 1.2 =
Add New Feature to edit PDF Header/Footer Text.

= 1.1 =
Add support Link.

= 1.0 =
Release version.