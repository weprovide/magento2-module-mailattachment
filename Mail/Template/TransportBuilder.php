<?php
namespace WeProvide\MailAttachment\Mail\Template;

use Zend_Mime;

/**
 * Class TransportBuilder
 * @package WeProvide\ServiceForm\Mail\Template
 */
class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{

    /**
     * Add Attachment to email
     *
     * @param $body
     * @param $mimeType
     * @param $disposition
     * @param $encoding
     * @param null $filename
     *
     * @return $this
     */
    public function addAttachment(
        $body,
        $mimeType = Zend_Mime::TYPE_OCTETSTREAM,
        $disposition = Zend_Mime::DISPOSITION_ATTACHMENT,
        $encoding = Zend_Mime::ENCODING_BASE64,
        $filename = null
    ) {
        $this->message->createAttachment($body, $mimeType, $disposition, $encoding, $filename);

        return $this;
    }

}