<?php
/**
 * 学生界面端异常
 */

namespace App\Exceptions;


class StuException extends ProgramException
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
        // 学生端自定义异常
        $message = $this->getMessage() ?: '操作失败';
        $code = $this->getCode() ?: 200;
        $redirect = $this->getRedirect() ?: '/';
        return $request->ajax() || $request->wantsJson() ?
            response()->json([ 'code'=>1,'msg' => $message,'data'=>null ],$code) :
            response(view('student.msg.index', compact('code', 'message', 'redirect')), $code);
    }
}