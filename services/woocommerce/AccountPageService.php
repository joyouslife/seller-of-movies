<?php
namespace jteam\services\woocommerce;

use jteam\core\abstracts\AbstractService;

class AccountPageService extends AbstractService
{
    public function getPageUrl()
    {
        $pageID = get_option('woocommerce_myaccount_page_id');

        return get_permalink($pageID);
    } // end getPageUrl
}