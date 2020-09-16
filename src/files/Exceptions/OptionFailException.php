<?php
/**
 * 操作失败异常
 */

namespace App\Exceptions;


class OptionFailException extends ProgramException
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
        // 未查询到数据
        $message = $this->getMessage() ?: '操作错误！';
        $code = $this->getCode() ?: 200;
        $redirect = $this->getRedirect() ?: '/';
        $isAjax = $request->ajax() || $request->wantsJson();
        if($isAjax){
            return response()->json([ 'code'=>201,'msg' => $message,'data'=>null ],$code);
        }else{
            return response(view('errors.404', compact('code', 'message', 'redirect')), $code);
        }
    }
}