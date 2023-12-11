<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lab404\Impersonate\Controllers\ImpersonateController;

class LeaveImpersonateController extends ImpersonateController
{
    /**
     * @return RedirectResponse
     */
    public function leave()
    {
        if (!$this->manager->isImpersonating()) {
            abort(403);
        }

        $this->manager->leave();

        session()->forget('restaurant');

        $leaveRedirect = $this->manager->getLeaveRedirectTo();
        if ($leaveRedirect !== 'back') {
            return redirect()->to($leaveRedirect);
        }
        return redirect()->back();
    }
}
