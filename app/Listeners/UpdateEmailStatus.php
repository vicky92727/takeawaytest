<?php

namespace App\Listeners;

use App\Events\JobProgressHasChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TransactionalEmail;
use Log;
class UpdateEmailStatus
{
    public $transEmail;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TransactionalEmail $transEmail)
    {
        $this->transEmail = $transEmail;
    }

    /**
     * Handle the event.
     *
     * @param  JobProgressHasChanged  $event
     * @return void
     */
    public function handle(JobProgressHasChanged $event)
    {
        $model = new TransactionalEmail();
        $model->updateEmailStatus($event->id,$event->response);

    }
}
