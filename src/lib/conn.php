<?php

namespace Biuio\Meter\lib;

class conn
{
    public $fd;
    public $option = array('baud'  =>  2400,    'bits'  =>  8,    'stop'   =>  1,    'parity'  =>  2);
    public $writeResult;
    public $readResult;
    public function __construct($uri, $option = array())
    {
        $this->open($uri);
    }
    public function open($uri)
    {
        $this->fd  =  dio_open($uri,  O_RDWR | O_NOCTTY | O_NONBLOCK);
        if (!$this->fd) {
            exit('串口打开失败');
        }
        if (dio_fcntl($this->fd,  F_SETLK, array("type" => F_WRLCK)) == -1) {
            exit('串口已经被其他应用占用' . PHP_EOL); // "The lock can not be cleared. It is held by someone else.\n";
        }
        // echo  "连接成功" . PHP_EOL; //Lock successfully set/cleared
        dio_tcsetattr($this->fd, $this->option);
    }
    public function req($cmd)
    {
        $this->writeResult = $this->write($cmd);
        usleep(0.6 * 1000 * 1000);
        $this->readResult = $this->read();
        return $this->readResult;
    }
    public function write($cmd)
    {
        return dio_write($this->fd, $cmd);
    }
    public function read($len = 1024)
    {
        return dio_read($this->fd, $len);
    }
    public function close()
    {
        return dio_close($this->fd);
    }
}
