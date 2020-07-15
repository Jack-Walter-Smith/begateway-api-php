<?php

namespace BeGateway;

/**
 * Class ResponseApiProduct
 *
 * @package BeGateway
 */
class ResponseApiProduct extends ResponseApi
{
    /**
     * @return string
     */
    public function getPayLink()
    {
        return implode('/',
            array(
                \BeGateway\Settings::$checkoutBase,
                'v2', 'confirm_order',
                $this->getId(),
                \BeGateway\Settings::$shopId,
            )
        );
    }

    /**
     * @return string
     */
    public function getPayUrl()
    {
        return implode('/',
            array(
                \BeGateway\Settings::$apiBase,
                'products',
                $this->getId(),
                'pay',
            )
        );
    }
}
