<?php

namespace meter645\lib;

class conn
{
    public $fd;
    public function __construct($uri)
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
        $option = array('baud'  =>  2400,    'bits'  =>  8,    'stop'   =>  1,    'parity'  =>  2);
        dio_tcsetattr($this->fd, $option);
    }
    public function req($cmd)
    {
        $this->write($cmd);
        // sleep(1);
        usleep(0.6 * 1000 * 1000);
        $res = $this->read();
        return $res;
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
