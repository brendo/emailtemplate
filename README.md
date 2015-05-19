# Email Template v0.2.2

[![Build Status](https://travis-ci.org/brendo/emailtemplate.svg?branch=master)](https://travis-ci.org/brendo/emailtemplate)

This component facilitates for the sending of emails using Swiftmailer.

## Usage
This library is made up of a few key concepts. A `Message`, `Prepare` and `Mailer`. All three
are required to construct the email, prepare the body and send the email. Optionally, you may
also choose to add `Attachment` to your `Message`.

---

### Message
Constructs the bare bones of an email, such as who it's going to, the subject line etc.

#### `Email`
The `Email` class is the most basic of all emails, and simply allows you to create an email
with `to`, `cc`, `bcc`, `from`, `subject` and `body` information. The default content type
for `Email` messages is 'text/plain'.

    $email = new EmailTemplate\Message\Email;
    $email->to(['your-email@example.com']);
    $email->from('system@example.com', 'The Webmaster');
    $email->subject('Hello there');
    $email->body('Hello there!');

#### `EmailTemplate`
The `EmailTemplate` class builds on the `Email` class, but instead allows you to load in
a template with placeholders. This template is then parsed and prepared with your data to
allow you to create dynamic emails. The default content type for `EmailTemplate` messages
is `text/html`.

    $emailTemplate = new EmailTemplate\Message\EmailTemplate('/path/to/email-templates');

    // Retrieve your email template (/path/to/email-templates/your-email-template.html)
    $emailTemplate->load('your-email-template')

    // You can specify a name if you like
    $emailTemplate->to(['your-email@example.com' => 'John Smith']);

    $emailTemplate->from('system@example.com');
    $emailTemplate->subject('Hello there');

##### Mesages with Fallback
You can specify both plain text and HTML alternatives to an email by specifiying a content type
when setting the body of the email:

    $email->body('<strong>This is HTML</strong>', 'text/html');
    $email->body('This is text', 'text/plain');

If the content type is omitted, the default content type will be used (`text/plain` for `Email` and
`text/html` for `EmailTemplate`)

---

### Attachment
All `Message` classes are able to support attachments via their `attachment` method. An attachment
can be either the path to the file, or the actual contents of the file itself. Multiple attachments
can be added with subsequent calls to the `attachment` method.

This example is a path to the attachment:

    // Create the attachment
    $attachment = new EmailTemplate\Attachment\Attachment;
    $attachment->filename('hello.txt');
    $attachment->data('/path/to/your/file', true);

    // Now attach it!
    $email->attachment($attachment);

This example is an inline attachment, an image called `waves.png`. Note the `disposition()` method
that will attempt to inline the image instead of just attaching it to the message.

    // Create the attachment
    $attachment = new EmailTemplate\Attachment\Attachment;
    $attachment->filename('waves.png');
    $attachment->data({wavesbinarydatahere});
    $attachment->contentType('image/png');
    $attachment->disposition('inline');

    // Now attach it!
    $email->attachment($attachment);


---

### Prepare
This is what generates the actual body of an email template. It should be used in conjuction with
`Message\EmailTemplate`.

#### `VsprintPrepare`
This is a very simple handler, which accepts the template and an array of parameters to be included.

It can be used standalone:

    $prepare = new \EmailTemplate\Prepare\VsprintfPrepare();
    $parsed = $prepare->render('Hi %s', array('there'));

Or assuming `$emailTemplate` from the above example:

    // This will be used to replace the placeholders in the template
    $emailTemplate->prepare(new \EmailTemplate\Prepare\VsprintfPrepare(), ['Brendan']);

    // Or you can inject the parsed template
    $emailTemplate->setParsedTemplate($parsed);

#### `MustachePrepare`
This allows you to markup your template using [Mustache](http://mustache.github.io/).

It can be used standalone:

    $prepare = new \EmailTemplate\Prepare\MustachePrepare();
    $parsed = $prepare->render('Hi {{name}}', array('name' => 'there'));

Assuming `$emailTemplate` from the above example:

    // This will be used to replace the placeholders in the template
    $emailTemplate->prepare(new \EmailTemplate\Prepare\MustachePrepare(), ['Brendan']);

    // Or you can inject the parsed template
    $emailTemplate->setParsedTemplate($parsed);


---

### Mailer
This is how the emails will actually be sent.

#### `SwiftMailer`
For the moment, sending of Emails is abstracted to use the `Swiftmailer` class.


    // Now lets prepare the mailer
    $mailer = new \EmailTemplate\Mailer();

    // Set the mail configuration
    $mailer->setConfiguration([
        'host' => 'your host',
        'port' => 'your port'
        'user' => 'your username',
        'password' => 'your password'
    ]);

    // If you want logging, you can pass a Monolog handler to do so
    $mailer->setLogger($logger);

    // The mailer can handle any email that implements the MessageInterface
    $mailer->send($email); // This can also be $emailTemplate
