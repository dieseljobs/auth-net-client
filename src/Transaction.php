<?php

namespace TheLHC\AuthNetClient;

class Transaction
{
    use GetsAndSetsAttributes;
    use ReturnsResponse;

    /**
     * Resolve XML payload for action
     *
     * @param  string $action
     * @return string
     */
    public function toXML($action)
    {
        switch ($action) {
            case "create":
                $template = "auth-net-client::create-transaction";
                break;
        }
        $xml = view(
            $template,
            ['transaction' => $this]
        )->render();
        return $xml;
    }

    /**
     * Add returned properties to instance after successful creation
     *
     * @param  Response $response
     * @return void
     */
    public function postCreateResponse($response)
    {
        foreach($response->transactionResponse as $key => $val) {
            $this->$key = $val;
        }
    }

}
