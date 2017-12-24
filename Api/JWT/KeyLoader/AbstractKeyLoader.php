<?php
/**
 * Created by PhpStorm.
 * User: Jafar Jabr
 * Date: 12/21/2017
 * Time: 9:38 PM
 */

namespace Jafar\Bundle\GuardedAuthenticationBundle\Api\JWT\KeyLoader;

abstract class AbstractKeyLoader implements KeyLoaderInterface
{
    const TYPE_PUBLIC  = 'public';
    const TYPE_PRIVATE = 'private';

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * Constructor.
     *
     * @param string $privateKey
     * @param string $publicKey
     * @param string $passphrase
     */
    public function __construct($passphrase)
    {
        $this->privateKey = dirname(__FILE__).'/Keys/private.pem';
        $this->publicKey  = dirname(__FILE__).'/Keys/public.pem';
        $this->passphrase = $passphrase;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }

    /**
     * @param string $type One of "public" or "private"
     *
     * @return string The path of the key
     *
     * @throws \InvalidArgumentException If the given type is not valid
     */
    protected function getKeyPath($type)
    {
        if (!in_array($type, [self::TYPE_PUBLIC, self::TYPE_PRIVATE])) {
            throw new \InvalidArgumentException(sprintf('The key type must be "public" or "private", "%s" given.', $type));
        }

        $path = null;

        if (self::TYPE_PUBLIC === $type) {
            $path = $this->publicKey;
        }

        if (self::TYPE_PRIVATE === $type) {
            $path = $this->privateKey;
        }

        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException(
                sprintf('%s key "%s" does not exist or is not readable. Did you correctly set the config option?', ucfirst($type), $path, $type)
            );
        }

        return $path;
    }
}