API integration
Sending requests
The integration of FedaPay’s payment solution relies on sending HTTPS requests to its servers. To ensure that your platform functions correctly in both test and production mode, it’s essential to understand how these requests should be structured, and what responses you can expect.
​
HTTPS request structure
When you interact with the FedaPay API, your HTTPS requests must follow a specific structure to be processed correctly. FedaPay provides two environments: a test environment and a live environment. Each environment has its own set of API keys (test and live), and each API key must be used according to the environment in which you are operating.
​
HTTPS methods supported
The FedaPay API supports several HTTP methods, the main ones being :

    POST : To send data and create new resources (e.g. a new transaction).
    GET : To retrieve existing information or resources (e.g. transaction details).
    PUT : Used to update an existing resource with new information (for example, to update transaction details).
    DELETE : Used to delete an existing resource (e.g. cancel a transaction).

​
Server URLs

    Test server
    Live server

Used to perform tests with no real impact on your account.

 https://sandbox-api.fedapay.com

​
Authentication
You must include your API key in the HTTP header of each request. Depending on whether you’re in test or live mode, you’ll use one of two keys: Authorization: Bearer YOUR_API_KEY
​
Test Mode vs Live Mode
​
Test mode
Before switching to Live mode, we recommend that you test your integration in test mode. This mode allows you to simulate various payment operations without carrying out any real transactions. You need to create a test account, and use the test API keys to address your requests to the test environment.

    Test API keys: These keys can only be used on the test server.
    Test transactions: Simulate transactions with fictitious cards and phone numbers to see how your system reacts.

​
Live mode
Once you’ve validated your tests, you can go live with your API keys. This mode enables you to carry out real transactions with your customers.
​
Mobile Money Test Numbers
To test your integration, FedaPay provides a single test mode called momo_test.This new setup simplifies sandbox testing by removing obsolete operator-specific test servers (MOOV, MTN, etc.). Test mode operation

    Active test mode: momo_test
    Success scenario: use only the numbers 64000001 and 66000001.
    Failure scenario: use any other number and the system will simulate a failed payment according to the parameters defined in your sandbox environment.

​
API responses
Every request you send to the FedaPay API receives a response, whether you’re in test or live mode. These responses are formatted in JSON and contain important information about the result of your request.
​
Successful response (200)
A successful response is usually an HTTP 200 code, and includes details of the resource created or consulted.
​
Response in case of error
If an error occurs (wrong API key, ill-formed request, etc.), the API returns a specific error code (for example, 400, 401, 404, 500).
​
Error management
For optimum management of your transactions, here are a few recommended practices in the event of errors:

    400 Bad Request: Check that your parameters are properly formatted and comply with API specifications.
    401 Unauthorized: Make sure you’re using the correct API key (test or live) and that it’s valid.
    404 Not Found: Check that the URL you are using is correct and that the resource exists.
    500 Internal Server Error: These errors originate from the FedaPay API. If they persist, please contact support.

Rigorously testing your integration with FedaPay before going live is essential to ensure smooth operation. Use the appropriate API keys, follow the request structure and take into account API responses to ensure successful integration.




API integration
Payouts management
A payout is an operation that allows you to transfer money directly from your balance to a customer’s account. This feature is designed for businesses that need to handle payments to specific clients.
​
Steps to Create a Payout
Creating a payout through the API involves several steps. Each payout follows processes that must be respected to ensure smooth transfer.
1

Payout Creation Request
To begin, you need to send a request to create a payout via our API. The essential information required in the request includes:

    amount : The amount for the payout, always in whole numbers.
    currency : The currency to be used for the payout. You can indicate the ISO code of the chosen currency (e.g., XOF for CFA francs).
    customer : The customer receiving the payout. If the customer is not yet in your database, you can create them at the same time by providing details such as name, email, and phone number.
    description (optional): A free-text field used to describe the purpose or intended use of the payout. This field can notably be used to:
        specify the nature of the transaction (e.g. family support, service payment, purchase of goods, etc.);
        comply with regulatory requirements, in particular those of the BCEAO regarding the justification of the use of transferred funds;
        facilitate transaction tracking, identification, and data analysis within the dashboard and via the API.

The description field is optional at the API level in order to ensure backward compatibility with existing integrations. However, it may be required in certain dashboard interfaces to improve the traceability of payout operations.
​
Example of a request for creating a repository

  curl -X POST \
  https://sandbox-api.fedapay.com/v1/payouts \
  -H 'Authorization: Bearer TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{
        "amount" : 2000,
        "currency" : {"iso" : "XOF"},
        "mode": "mtn_open" , 
        "description": "service payment" , // Not required
        "customer" : {
            "firstname" : "John",
            "lastname" : "Doe",
            "email" : "john.doe@example.com",
            "phone_number" : {
                "number" : "+22997808080",
                "country" : "bj"
            }
        }
      }'

Find more information in our API ReferenceSample code is available to simplify the creation of a repository via the API. Be sure to replace YOUR_SECRETE_API_KEY with your sandbox or live private key.
Note:The customer parameter is not mandatory. However, when creating a transaction with a customer, make sure that the email address is unique. FedaPay considers it to be the same customer if the emails are identical. If you send the same email address but enter different first and last names and phone numbers, FedaPay will simply update the customer with the new information.
2

Sending the Payout
Once a payout has been created, it will be marked as pending. You then need to proceed with sending the payout. Two options are available:

    Send the payout immediately.
    Schedule the payout for later.

    curl -X PUT \
https://sandbox-api.fedapay.com/v1/payouts/start \
-H 'Authorization: Bearer TOKEN' \
-H 'Content-Type: application/json'
-d '{
    "payouts" : [
      { "id": 23 }, // Sends the deposit instantly
      { "id": 23, "phone_number": { "number": "66000001", "country": "BJ" } }, // Sends deposit instantly with phone number
      { "id": 24 , "scheduled_at": "2024-11-18 18:8:43"} // Send payout later
    ]
  }'

3

Retrieve Payout Details
After creating and/or sending the payout, you can view its details to obtain specific information, such as status or payout history.
​
Example query to retrieve payout details


/*Replace YOUR_API_SECRET_KEY by the API secret key of your sandbox or live account. If you are using your live account, you must replace the link by https://api.fedapay.com/v1/payouts/ID   */

  curl -X GET \
  https://sandbox-api.fedapay.com/v1/payouts/ID \
  -H 'Authorization: Bearer TOKEN' \
  -H 'Content-Type: application/json'

Find more information in our API ReferenceRetrieve information on a specific repository using its unique identifier (ID). Replace ID in the URL with the ID of the repository you wish to consult.
​
Payout Lifecycle
When a payout is created, it moves through several statuses:

    pending : Pending (initial status after payout creation).
    started : The payout has been validated and is in the process of being initiated.
    processing : The payout is being processed and sent to the recipient.
    sent : The payout has been successfully sent to the recipient.
    failed : The payout failed due to issues such as technical error or method-related problems.

You can track the status of your payouts from the FedaPay dashboard under the Payouts section.
​
Available Payout Methods
Currently, the available payout methods for sending funds to different countries in West Africa are supported These methods make it easy to send money to various countries in the region.
​
Add Custom Metadata to Your Payouts
When initiating a payout from your FedaPay balance to a Mobile Money account (MTN Benin, MTN Côte d’Ivoire, Moov Benin, Moov Togo, or Togocel), you can attach custom metadata to the operation using the custom_metadata field. This allows you to enrich each payout with useful business-related information, such as a user ID, a transfer purpose, a service reference, or any data specific to your system. Why Use custom_metadata in a Payout? The custom_metadata field is particularly helpful for:

    Keeping a clear and structured trace of each transaction within your platform.
    Automating internal processes by linking payouts to users, orders, or events.
    Simplifying audits and reconciliation with metadata stored directly in the transaction.
    Saving time by avoiding manual cross-referencing between FedaPay IDs and your own records.

Example: Perform a Payout with Custom Metadata Here’s a sample API request to send a payout with attached metadata:

  curl -X POST \
-H 'Authorization: Bearer TOKEN' \
-H 'Content-Type: application/json' \
-d '{
"amount": 3000,
"currency": {"iso": "XOF"},
"description": "Paiement de la commission d’un agent terrain",
"receiver": {
"name": "Koffi Kodjo",
"phone_number": "+22961234567",
"provider": "mtn"
},
"custom_metadata": {
"agent_id": "AGT-0032",
"mois": "Juillet",
"type": "commission"
}
}'

Good to Know

    The custom_metadata field accepts a JSON object with key-value pairs.
    Use simple and meaningful keys like type, month, client_id.
    This field is optional, but strongly recommended for better data management and traceability.
    Avoid including sensitive or confidential information, such as passwords or card details.

​
merchant_reference: Your unique identifier for each payout
The merchant_reference field allows you to assign each payout operation (transfer of money from your FedaPay merchant account to a Mobile Money number ) a unique identifier defined by you.This identifier is stored by FedaPay and can later be used to easily retrieve or track a payout through a dedicated API. Why use it?

    Precise tracking of outgoing transfers: Ideal if you manage multiple payments to suppliers, partners, couriers, or users.
    Internal traceability: Lets you link a FedaPay payout to an internal payment (for example, a refund, commission, or salary payment).
    Simplified search: Retrieve a payout at any time using its merchant_reference, without needing to store the FedaPay-generated ID.
    Audit & reporting: Very useful for generating reports or ensuring the accounting and financial compliance of your outgoing flows.

Example: Create a payout with merchant_reference

curl -X POST https://sandbox-api.fedapay.com/v1/payouts \
-H 'Authorization: Bearer YOUR_SECRET_API_KEY' \
-H 'Content-Type: application/json' \
-d '{
"amount": 5000,
"currency": {"iso": "XOF"},
"description": "Paiement fournisseur Mars 2025",
"recipient": {
  "name": "Kossi Agbo",
  "phone_number": {
    "number": "97000001",
    "country": "bj"
  }
},
"merchant_reference": "PAY-20250315-002"
}'

Retrieve a payout using the merchant reference

curl -X GET \
https://sandbox-api.fedapay.com/v1/payouts/merchant/PAY-20250315-002 \
-H 'Authorization: Bearer YOUR_SECRET_API_KEY' \
-H 'Content-Type: application/json'







API integration
Webhooks and Events
​
Event Management
Events are important actions that occur within your FedaPay account, such as the creation of a transaction or an update to a customer. Understanding how these events work allows you to better manage your transactions and provide a better experience for your customers. Whenever an event occurs, FedaPay notifies you in real-time through event notifications. These notifications can be used to track and react to what is happening within your account, such as when a payment is approved or when a customer is updated.
​
Transaction Lifecycle
Transactions on FedaPay follow a lifecycle, and each stage in this lifecycle generates a specific event. Here’s how it works:

    Transaction Creation : Once the customer is created, you can assign a transaction to them. This triggers the transaction.created event.
    Tracking Transactions: A transaction can evolve in several ways:
        transaction.approved : The transaction has been approved, meaning the payment has been validated.
        customer.created : When a new customer is added to your account, this generates the event
        transaction.declined: The payment failed or was rejected.
        transaction.canceled: The transaction was canceled before it was finalized.
        transaction.transferred: The funds from the transaction have been transferred to the designated account (e.g., bank account or mobile money).
    À chaque changement de statut, un nouvel événement est généré pour vous tenir informé. Par exemple, dès qu’une transaction est mise à jour, l’événement transaction.updated est déclenché.

​
Customer Lifecycle
Customers can also have specific events:

    customer.updated : The customer’s profile has been modified (e.g., their name or email address).
    customer.deleted : The customer has been deleted from your account.

​
How to Use Events ?
Each event contains detailed information about what has just happened. You can view all these events in the Events section of your FedaPay dashboard, which gives you a complete history of all important actions in your account. images d’illlustrations de la présentation des Evènements au niveau du tableau de bord
​
Why is it Important ?
Event management helps you to:

    Track Payments: You are informed in real-time of the status of each transaction.
    Manage Customers: You can track changes made to customer profiles.
    Automate Processes: With event notifications, you can automate certain tasks on your site, like sending a confirmation email after a successful payment.

​
Introduction to Webhooks
Webhooks are automatic notifications that FedaPay sends to your application or website when important events occur on your account. For example, you can receive a webhook when a transaction is successful or disputed. These notifications are particularly useful because they allow you to stay informed in real-time without having to manually check what is happening on your FedaPay account.
​
Why Use Webhooks ?
Webhooks are essential to be quickly alerted of important actions on your account, such as:

    Successful or failed payments.
    Refunds.
    Disputed transactions.

​
How Do Webhooks Work?
Each time an event occurs (e.g., an accepted payment), FedaPay creates an Event object. This object contains all relevant information about the event, such as the event type (successful payment) and associated details. Then, FedaPay sends this object to your chosen URL (called the endpoint) via an HTTP request. It is like FedaPay sending you a message to inform you of what has happened.
​
Configuring Webhooks
1

Creating a Webhook
To receive Webhooks, you need to configure a URL on your site that can receive these notifications. Follow these steps:

    Log in to your FedaPay account.
    Go to the Webhooks section from the dashboard menu.
    Click on Create a Webhook or New Webhook.
    A form opens with several fields to fill in:

Enter the destination URL

    Enter the URL of your site where you want to receive notifications.
    Ensure that this URL is ready to handle Webhooks sent by FedaPay.

Optional settings:

    Disable SSL verification on HTTP requests (optional).
    Disable the Webhook when the application generates errors (optional).

Add HTTP headers

    You can specify custom headers by adding key-value pairs.
    Click the ”+” button to add multiple headers if needed.

Select event types

    Receive all events (by default, you will receive all notifications).
    Select specific events (you can choose only those of interest from the available event list).

Finalization and activation

    Verify that all information is correct.
    Click Create to save and activate the Webhook.

2

Managing Your Webhooks
Once your Webhooks are created, you can:

    Modify: Change the URL or the events you want to track.
    Delete: If you no longer need this Webhook, you can remove it.
    View details: You can see all information about the Webhook (URL, tracked events, etc.).

​
Webhook Event Delivery Strategy
When you define a Webhook endpoint, FedaPay will send event notifications related to that endpoint when they are triggered on FedaPay.

    FedaPay sends an HTTP POST request with the event data.
    FedaPay expects a 2xx status response.
    Any response other than 2xx is considered a failure.

​
Automatic Retry Mechanism
FedaPay executes each webhook event delivery in concurrent tasks.

    If execution fails, FedaPay will retry up to 9 times at exponential intervals.
    The retry waiting time does not exceed 2 minutes.
    After 10 unsuccessful attempts, the Webhook is automatically disabled to prevent queue overload.
    You can prevent automatic deactivation by unchecking the option Disable the webhook when the application generates errors in your dashboard.

It is advisable to monitor your system closely and prevent potential errors. Follow our recommendations for optimal service implementation.
​
Manual Redelivery

    Go to the Logs page of your webhook.
    Click the Redeliver button.

​
Best Practices for Using Webhooks
​
Handle Duplicate Events
Webhook endpoints may sometimes receive the same event multiple times. You can prevent processing duplicate events by following these guidelines:

    Store the event IDs of already processed events to avoid reprocessing them.
    Use the object identifier in object and name to detect duplicates.

​
Listen Only to Required Event Types

    Configure your endpoints to receive only necessary events.
    Avoid listening to all events to prevent server overload.
    Modify received events through the dashboard.

​
Handle Events Asynchronously
To prevent scalability issues and ensure system stability, follow these recommendations:

    Use an asynchronous queue to process webhook events without blocking your system.
    Avoid synchronous processing, which may slow down your infrastructure and cause failures under heavy load.
    Anticipate traffic spikes, especially during mass subscription renewals, to prevent server overload.
    Control processing rates by adjusting event consumption based on your system’s capacity.

​
Receiving Events with an HTTPS Server
To ensure webhook security, make sure your server meets the following requirements:

    Use an HTTPS URL: FedaPay verifies the security of the connection before sending webhooks.
    Valid SSL certificate: Your server must be properly configured with a valid certificate to prevent connection rejection.
    TLS compatibility: Only TLS v1.2 and v1.3 versions are supported by FedaPay.

​
Verifying That Events Are Sent by FedaPay
1

IP Address Verification

    FedaPay sends webhooks from a predefined list of IP addresses.
    Only trust events originating from these addresses.

2

Webhook Signature Verification

    Each webhook is signed by FedaPay via the X-FEDAPAY-SIGNATURE header.
    You can verify these signatures using:
        Official FedaPay libraries.
        Manual verification with your own solution.

3

How to Verify Webhook Signatures ?
Retrieve the Endpoint Secret

    Go to Workbench → Webhooks Tab.
    Select the endpoint and click Click to reveal.
    FedaPay generates a unique secret key for each endpoint:
        Different between test mode and live mode.
        Unique for each used endpoint.

Signature Verification

    When a Webhook is sent, FedaPay includes a signature in the request header.
    This signature is present in the X-FEDAPAY-SIGNATURE header.
    PTo verify that the message is authentic: 1- Use your Webhook secret key (retrievable from dashboard settings). 2- Use this key to verify the signature and ensure the Webhook is from FedaPay.

Tools for Signature Verification

    To ensure that received Webhooks originate from FedaPay and have not been altered, it is essential to verify their signature.
    FedaPay simplifies this process with its official libraries.
    Here is an example of code showing how to verify the signature in a Node.js or PHP

const { Webhook } = require('fedapay')

// You can find your endpoint's secret key in your webhook settings
const endpointSecret = 'wh_sandbox...';

// This example uses Express to receive webhooks
const app = require('express')();

// Use body-parser to retrieve the raw body as a buffer
const bodyParser = require('body-parser');

// Match the raw body to content type application/json
app.post('/webhook', bodyParser.raw({type: 'application/json'}), (request, response) => {
  const sig = request.headers['x-fedapay-signature'];

  let event;

  try {
    event = Webhook.constructEvent(request.body, sig, endpointSecret);
  } catch (err) {
    response.status(400).send(`Webhook Error: ${err.message}`);
  }

  // Handle the event
  switch (event.name) {
    case 'transaction.created':
      // Transaction créée
      break;
    case 'transaction.approved'':
      // Transaction approuvée
      break;
    case 'transaction.canceled'':
      // Transaction annulée
      break;
    default:
      console.log(`Unhandled event type ${event.type}`);
  }

  // Return a response to acknowledge receipt of the event
  response.json({received: true});
});

app.listen(4242, () => console.log('Running on port 4242'));

4

Prevent Replay Attacks

    A replay attack consists of resending an intercepted webhook.
    FedaPay includes a timestamp in the X-FEDAPAY-SIGNATURE header to prevent these attacks.
    The timestamp is checked with the signature:
        It cannot be modified without invalidating the signature.
        If the timestamp is too old, your application can reject the webhook.
    Each retry attempt (if the first one fails) generates a new signature and timestamp.

5

Respond Quickly with a 2xx Status

    Your endpoint should respond quickly with a 2xx status before performing heavy processing.
    Exemples :
        Respond with 200 immediately.
        Then, perform actions such as marking an invoice as paid.
        