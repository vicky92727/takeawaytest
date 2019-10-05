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

    private $to;
    private $recipientname;
    private $emailid;
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
        $this->emailid = $request->id;
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

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $mailin = new \Sendinblue\Mailin("https://api.sendinblue.com/v2.0",env("API_KEY_V3"));
        $data = array( "to" => array($this->to=> $this->recipientname),
            "from" => array("admin@takeaway.com", "Admin"),
            "subject" => "Test Email through Mailinblue",
            "html" => "This is the <h1>HTML</h1>"
        );

        var_dump($mailin->send_email($data));
    }
}
