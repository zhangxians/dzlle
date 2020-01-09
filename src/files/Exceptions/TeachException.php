<?php
/**
 * 教师界面端异常
 */

namespace App\Exceptions;


class TeachException extends ProgramException
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Report the exception.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function render($request)
    {
        // 教师端自定义异常
        $message = $this->getMessage() ?: '操作失败';
        $code = $this->getCode() ?: 200;
        $redirect = $this->getRedirect() ?: '/';
        return $request->ajax() || $request->wantsJson() ?
            response()->json([ 'code'=>1,'msg' => $message,'data'=>null ],$code) :
            response(view('teacher.msg.index', compact('code', 'message', 'redirect')), $code);
    }
}