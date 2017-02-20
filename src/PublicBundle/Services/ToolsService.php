<?php

namespace PublicBundle\Services;

class ToolsService
{
    private $function = array(
        'getRandCharService',
        'sendEmailService',
    );
    private $templating;
    private $mailer;

    public function __construct($templating, $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    /**
     * 获取一个固定长度的随机字符串
     * @param integer $length 字符串长度
     *
     * @return string $str 字符串
     */
    public function getRandCharService($length)
    {
        $str = null;
        $strPol = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($strPol) - 1;

        for($i = 0; $i < $length; $i++){
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }

    /**
     * 发送一封邮件
     * @param array $emailData 待发邮件数据
     *
     */
    public function sendEmailService($emailData){
        $message = \Swift_Message::newInstance()
            ->setSubject($emailData['subject'])
            ->setFrom('wiki@joywell.com.cn')
            ->setTo($emailData['emailAdd'])
            ->setBody(
                $this->templating->render(
                    $emailData['view'],
                    $emailData['arr']
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
