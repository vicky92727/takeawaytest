<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
class TransactionalEmail extends Model
{
	const EMAIL_PENDING = 0;
	const EMAIL_SENT = 1;
	const EMAIL_FAILED = 2;
	const SUCCESS = 202;
	const ERROR = 401;
    protected $fillable = ['subject','content', 'status'];

    public function saveEmailStatus($request) {
        
        $this->status = self::EMAIL_PENDING;

        $this->subject = $request->subject;

        $this->content = $request->content;

        if ($this->save()) {
            return $this->id;
        }
        return false;
    }

    public function updateEmailStatus($id, $status) {
        if (empty($id)) {
            return false;
        }

        Log::info('Update Email Variables  --> ' .$id.'---------'. $status);
        if ($status=='202') {

        	$this->status = self::EMAIL_SENT;

        } else {

        	$this->status = self::EMAIL_FAILED;
        }
        $result = TransactionalEmail::whereId($id)->update(array('status' => $this->status));
        return $result;
    }
}
