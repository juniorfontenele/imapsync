<?php

namespace JuniorFontenele\Imapsync;

use Exception;

class Imapsync
{
    protected static $codes = [
        0 => 'EX_OK',
        64 => 'EX_USAGE',
        66 => 'EX_NOINPUT',
        69 => 'EX_UNAVAILABLE',
        70 => 'EX_SOFTWARE',
        1 => 'EXIT_CATCH_ALL',
        6 => 'EXIT_BY_SIGNAL',
        7 => 'EXIT_BY_FILE',
        8 => 'EXIT_PID_FILE_ERROR',
        10 => 'EXIT_CONNECTION_FAILURE',
        12 => 'EXIT_TLS_FAILURE',
        16 => 'EXIT_AUTHENTICATION_FAILURE',
        21 => 'EXIT_SUBFOLDER1_NO_EXISTS',
        111 => 'EXIT_WITH_ERRORS',
        112 => 'EXIT_WITH_ERRORS_MAX',
        113 => 'EXIT_OVERQUOTA',
        114 => 'EXIT_ERR_APPEND',
        115 => 'EXIT_ERR_FETCH',
        116 => 'EXIT_ERR_CREATE',
        117 => 'EXIT_ERR_SELECT',
        118 => 'EXIT_TRANSFER_EXCEEDED',
        119 => 'EXIT_ERR_APPEND_VIRUS',
        254 => 'EXIT_TESTS_FAILED',
        101 => 'EXIT_CONNECTION_FAILURE_HOST1',
        102 => 'EXIT_CONNECTION_FAILURE_HOST2',
        161 => 'EXIT_AUTHENTICATION_FAILURE_USER1',
        162 => 'EXIT_AUTHENTICATION_FAILURE_USER2',
    ];

    protected static bool $dry = false;
    protected static bool $log = false;
    protected static ?string $logfilename = null;
    protected static ?string $logdir = null;
    protected static ?int $timeout1 = null;
    protected static ?int $timeout2 = null;
    protected static ?string $host1 = null;
    protected static ?string $host2 = null;
    protected static ?int $port1 = null;
    protected static ?int $port2 = null;
    protected static ?string $authUser1 = null;
    protected static ?string $authUser2 = null;
    protected static ?string $user1 = null;
    protected static ?string $user2 = null;
    protected static ?string $password1 = null;
    protected static ?string $password2 = null;
    protected static ?string $pipemess = null;
    protected static bool $nossl1 = false;
    protected static bool $nossl2 = false;
    protected static bool $xoauth1 = false;
    protected static bool $xoauth2 = false;
    protected static bool $gmail1 = false;
    protected static bool $gmail2 = false;
    protected static bool $office1 = false;
    protected static bool $office2 = false;
    protected static bool $nofoldersizes = false;
    protected static bool $nofoldersizesatend = false;
    protected static ?int $maxsleep = null;
    protected static string $command;
    protected static string $imapsyncBinary;
    protected static ?Imapsync $_instance = null;

    private function __construct() { }

    public static function create(string $imapsyncBinary = 'imapsync'): Imapsync
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        self::$imapsyncBinary = $imapsyncBinary;
        return self::$_instance;
    }

    public static function getCodeMessage(int $code): string
    {
        return self::$codes[$code];
    }

    public function simulate(bool $simulate = true): Imapsync
    {
        return $this->dry($simulate);
    }

    public function dry(bool $dry = true): Imapsync
    {
        self::$dry = $dry;
        return $this;
    }

    public function log(bool $log = true): Imapsync
    {
        self::$log = $log;
        return $this;
    }

    public function logdir(?string $logdir): Imapsync
    {
        self::$logdir = $logdir;
        return $this;
    }

    public function logfilename(?string $logfilename): Imapsync
    {
        self::$logfilename = $logfilename;
        return $this;
    }

    public function pipemess(?string $pipemess): Imapsync
    {
        self::$pipemess = $pipemess;
        return $this;
    }

    public function timeout1(?int $timeoutSeconds): Imapsync
    {
        self::$timeout1 = $timeoutSeconds;
        return $this;
    }

    public function timeout2(?int $timeoutSeconds): Imapsync
    {
        self::$timeout2 = $timeoutSeconds;
        return $this;
    }

    public function host1(string $host): Imapsync
    {
        self::$host1 = $host;
        return $this;
    }

    public function host2(string $host): Imapsync
    {
        self::$host2 = $host;
        return $this;
    }

    public function port1(?int $imapPort): Imapsync
    {
        self::$port1 = $imapPort;
        return $this;
    }

    public function port2(?int $imapPort): Imapsync
    {
        self::$port2 = $imapPort;
        return $this;
    }

    public function authUser1(?string $adminUser): Imapsync
    {
        self::$authUser1 = $adminUser;
        return $this;
    }

    public function authUser2(?string $adminUser): Imapsync
    {
        self::$authUser2 = $adminUser;
        return $this;
    }

    public function user1(string $user): Imapsync
    {
        self::$user1 = $user;
        return $this;
    }

    public function user2(string $user): Imapsync
    {
        self::$user2 = $user;
        return $this;
    }

    public function password1(string $password): Imapsync
    {
        self::$password1 = $password;
        return $this;
    }

    public function password2(string $password): Imapsync
    {
        self::$password2 = $password;
        return $this;
    }

    public function nossl1(bool $nossl = true): Imapsync
    {
        self::$nossl1 = $nossl;
        return $this;
    }

    public function nossl2(bool $nossl = true): Imapsync
    {
        self::$nossl2 = $nossl;
        return $this;
    }

    public function xoauth1(bool $xoauth = true): Imapsync
    {
        self::$xoauth1 = $xoauth;
        return $this;
    }

    public function xoauth2(bool $xoauth = true): Imapsync
    {
        self::$xoauth2 = $xoauth;
        return $this;
    }

    public function gmail1(bool $gmail1 = true): Imapsync
    {
        self::$gmail1 = $gmail1;
        return $this;
    }

    public function gmail2(bool $gmail2 = true): Imapsync
    {
        self::$gmail2 = $gmail2;
        return $this;
    }

    public function office1(bool $office1 = true): Imapsync
    {
        self::$office1 = $office1;
        return $this;
    }

    public function office2(bool $office2 = true): Imapsync
    {
        self::$office2 = $office2;
        return $this;
    }

    public function nofoldersizes(bool $nofoldersizes = true): Imapsync
    {
        self::$nofoldersizes = $nofoldersizes;
        return $this;
    }

    public function nofoldersizesatend(bool $nofoldersizesatend = true): Imapsync
    {
        self::$nofoldersizesatend = $nofoldersizesatend;
        return $this;
    }

    public function maxsleep(int $maxsleep = 2): Imapsync
    {
        self::$maxsleep = $maxsleep;
        return $this;
    }

    public function getCommand(): string
    {
        if (!self::$host1 || !self::$host2 || !self::$password1 || !self::$password2 || !self::$user1 || !self::$user2) {
            throw new Exception('Insuficient parameters');
        }
        $command = self::$imapsyncBinary;
        if (self::$dry) {
            $command .= ' --dry';
        }
        if (self::$log) {
            $command .= ' --log';
        }
        if (self::$logdir) {
            $command .= ' --logdir "' . self::$logdir . '"';
        }
        if (self::$logfilename) {
            $command .= ' --logfile "' . self::$logfilename . '"';
        }
        if (self::$pipemess) {
          $command .= ' --pipemess "' . self::$pipemess . '"';
      }
        if (self::$timeout1) {
            $command .= ' --timeout1 ' . self::$timeout1;
        }
        if (self::$timeout2) {
            $command .= ' --timeout2 ' . self::$timeout2;
        }
        if (self::$nossl1) {
            $command .= ' --nossl1';
        }
        if (self::$nossl2) {
            $command .= ' --nossl2';
        }
        if (self::$xoauth1) {
            $command .= ' --authmech1 XOAUTH2';
        }
        if (self::$xoauth2) {
            $command .= ' --authmech2 XOAUTH2';
        }
        if (self::$authUser1) {
            $command .= ' --authuser1 "' . self::$authUser1 . '"';
        }
        if (self::$authUser2) {
            $command .= ' --authuser2 "' . self::$authUser2 . '"';
        }
        if (self::$gmail1) {
          $command .= ' --gmail1';
        }
        if (self::$gmail2) {
          $command .= ' --gmail2';
        }
        if (self::$office1) {
          $command .= ' --office1';
        }
        if (self::$office2) {
          $command .= ' --office2';
        }
        if (self::$office1) {
          $command .= ' --office1';
        }
        if (self::$nofoldersizes) {
          $command .= ' --nofoldersizes';
        }
        if (self::$nofoldersizesatend) {
          $command .= ' --nofoldersizesatend';
        }
        if (self::$maxsleep) {
          $command .= ' --maxsleep ' . self::$maxsleep;
        }
        $command .= ' --host1 "' . self::$host1 . '"';
        $command .= ' --host2 "' . self::$host2 . '"';
        $command .= ' --user1 "' . self::$user1 . '"';
        $command .= ' --user2 "' . self::$user2 . '"';
        $command .= ' --password1 "' . self::$password1 . '"';
        $command .= ' --password2 "' . self::$password2 . '"';

        return escapeshellcmd($command);
    }

    public function run(array &$output = []): int
    {
        $command = $this->getCommand();
        exec($command, $output, $resultCode);
        return $resultCode;
    }
}
