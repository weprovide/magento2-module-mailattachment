# Magento2 Module MailAttachment

Extends TransportBuilder with attachment functionality

## Installation

1. `composer require weprovide/magento2-module-mailattachment`
2. `bin/magento setup:upgrade`

## Usage example

```php
<?php
namespace YourNameSpace\YourModule\Controller\Email;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\Store;
use WeProvide\MailAttachment\Mail\Template\TransportBuilder;
use Zend_Mime;

class Index extends Action
{
    protected $transportBuilder;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder
    ) {
        $this->transportBuilder  = $transportBuilder;
        parent::__construct($context);
    }

    /**
     * Execute view action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Any buffer
        $content = '';

        $transport = $this->transportBuilder->setTemplateIdentifier('example_identifier')
                                            ->setTemplateOptions([
                                                'area'  => Area::AREA_FRONTEND,
                                                'store' => Store::DEFAULT_STORE_ID,
                                            ])
                                            ->setFrom([
                                                'name'  => 'Example name',
                                                'email' => 'info@example.com',
                                            ])
                                            ->addTo('example@example.com')
                                            ->addAttachment($content, 'document.pdf', 'application/pdf');
        
        $transport = $transport->getTransport();
        $transport->sendMessage();

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
```

## Api

`public function addAttachment(
    $content,
    $fileName = '',
    $fileType = ''
)`

For reference also check [the code](Mail/Template/TransportBuilder.php)
