<?php

namespace WeProvide\MailAttachment\Mail\Template;

use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Zend\Mime\MessageFactory as MimeMessageFactory;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\Mime\PartFactory;


/**
 * Class TransportBuilder
 * @package WeProvide\ServiceForm\Mail\Template
 */
class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    /**
     * @var Part[]
     */
    protected $attachments = [];

    /**
     * @var PartFactory
     */
    protected $partFactory;

    /**
     * @var MessageInterfaceFactory
     */
    protected $messageFactory;

    /**
     * @var MimeMessageFactory
     */
    protected $mimeMessageFactory;

    /**
     * TransportBuilder constructor.
     * @param PartFactory $partFactory
     * @param MimeMessageFactory $mimeMessageFactory
     * @param FactoryInterface $templateFactory
     * @param MessageInterface $message
     * @param SenderResolverInterface $senderResolver
     * @param ObjectManagerInterface $objectManager
     * @param TransportInterfaceFactory $mailTransportFactory
     * @param MessageInterfaceFactory|null $messageFactory
     */
    public function __construct(
        PartFactory $partFactory,
        MimeMessageFactory $mimeMessageFactory,
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        MessageInterfaceFactory $messageFactory = null
    ) {
        parent::__construct(
            $templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory,
            $messageFactory
        );

        $this->partFactory        = $partFactory;
        $this->mimeMessageFactory = $mimeMessageFactory;
    }

    /**
     * @return \Magento\Framework\Mail\Template\TransportBuilder|void
     */
    protected function reset(): void
    {
        parent::reset();
        $this->attachments = [];
    }

    /**
     * After all parts are set, add them to message body.
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareMessage()
    {
        parent::prepareMessage();
        $this->attachAttachmentsToBody();

        return $this;
    }

    protected function attachAttachmentsToBody()
    {
        if (!empty($this->attachments)) {
            // @TODO needs to be replaced when upgrading to 2.3.3. :(
            // @see mimePartInterfaceFactory
            // @see https://github.com/magento/magento2/pull/24906/files
            // @see https://magento.stackexchange.com/questions/292760/unable-to-add-attachment-in-email-after-upgrade-to-magento-2-3-3-version

            $mimeMessage = $this->mimeMessageFactory->create();
            $mimeMessage->setParts(array_merge($this->message->getBody()->getParts(), $this->attachments));
            $this->message->setBody($mimeMessage);
        }
    }

    /**
     * @param string|null $content
     * @param string|null $fileName
     * @param string|null $fileType
     * @return TransportBuilder
     */
    public function addAttachment(string $content, string $fileName, string $fileType)
    {
        /** @var Part $attachmentPart */
        $attachmentPart = $this->partFactory->create();
        $attachmentPart->setContent($content)
            ->setType($fileType)
            ->setFileName($fileName)
            ->setDisposition(Mime::DISPOSITION_ATTACHMENT)
            ->setEncoding(Mime::ENCODING_BASE64);

        $this->attachments[] = $attachmentPart;

        return $this;
    }
}
