# Email Template v0.1.0

This component facilitates for the sending of emails using Swiftmailer.

## Usage
This library is made up of a few key concepts. A `Message`, `Prepare` and `Mailer`. All three
are required to construct the email, prepare the body and send the email.

### `Message`
Constructs the bare bones of an email, such as who it's going to, the subject line etc.

#### `Email`
The `Email` class is the most basic of all emails, and simply allows you to create an email
with `to`, `cc`, `bcc`, `from`, `subject` and `body` information.

    $email = new EmailTemplate\Message\Email;
    $email->to(['your-email@example.com']);
    $email->from('system@example.com', 'The Webmaster');
    $email->subject('Hello there');
    $email->body('Hello there!');

#### `EmailTemplate`
The `EmailTemplate` class builds on the `Email` class, but instead allows you to load in
a template with placeholders. This template is then parsed and prepared with your data to
allow you to create dynamic emails.

    $emailTemplate = new EmailTemplate\Message\EmailTemplate('/path/to/email-templates');

    // Retrieve your email template (/path/to/email-templates/your-email-template.html)
    $emailTemplate->load('your-email-template')

    // You can specify a name if you like
    $emailTemplate->to(['your-email@example.com' => 'John Smith']);

    $emailTemplate->from('system@example.com');
    $emailTemplate->subject('Hello there');

### `Prepare`
This is what generates the actual body of an email template. It should be used in conjuction with `Message\EmailTemplate`.

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

### `Mailer`
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
