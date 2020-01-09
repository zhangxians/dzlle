<?php
/**
 * Model字段验证异常
 */

namespace App\Exceptions;


class ValidateException extends ProgramException
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
        // 字段验证异常
        $message = $this->getMessage() ?: '字段不符合规格，验证失败';
        $code = $this->getCode() ?: 200;
        $redirect = $this->getRedirect() ?: '/';
        return $request->ajax() || $request->wantsJson() ?
            response()->json([ 'code'=>1,'msg' => $message,'data'=>null ],$code) :
            response(view('errors.400', compact('code', 'message', 'redirect')), $code);
    }
}