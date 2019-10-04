<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class SendTransactionalEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to;
    public $recipientname;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->to = $request->to;
        $this->recipientname = $request->recipientname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("admin@takeaway.com", "admin");
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addTo($this->to, $this->recipientname);
        $email->addContent(
            "text/plain", "and easy to do anywhere, even with PHP"
        );
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            Log::info('Sendingg Email through Send Grid API --> ' . $response->statusCode());
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
            //return response()->json($response, 201);

            /*if ($response->statusCode() == '401') {
                # Instantiate the client\
                SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey("api-key", env("API_KEY_V3"));

                $api_instance = new SendinBlue\Client\Api\EmailCampaignsApi();
                $emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();

                # Define the campaign settings\
                $email_campaigns['name'] = "Campaign sent via the API";
                $email_campaigns['subject'] = "My subject";
                $email_campaigns['sender'] = array("name": "Admin", "email":"admin@takeaway.com");
                $email_campaigns['type'] = "classic";

                    # Content that will be sent\
                    "htmlContent"=> "Congratulations! You successfully sent this example campaign via the SendinBlue API.",

                    # Select the recipients\
                    "recipients"=> array("listIds"=> [2, 7]),

                    # Schedule the sending in one hour\
                    "scheduledAt"=> "2018-01-01 00:00:01"
                );

                # Make the call to the client\
                try {
                    $result = $api_instance->createEmailCampaign($emailCampaigns);
                    print_r($result);
                } catch (Exception $e) {
                    echo 'Exception when calling EmailCampaignsApi->createEmailCampaign: ', $e->getMessage(), PHP_EOL;
                }
            }*/
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

        /**
         * Determine the time at which the job should timeout.
         *
         * @return \DateTime
         */
        public function retryUntil()
        {
            return now()->addSeconds(5);
        }
}
