<?php

class ServiceContainer
{
    public static $services = array();

    public static function getService($serviceName)
    {
        if (!array_key_exists($serviceName, self::$services)) {
            $serviceClass = ucfirst($serviceName) . 'Service';
            self::$services[$serviceName] = new $serviceClass;
        }

        return self::$services[$serviceName];
    }

    /**
     * @return CurrencyService
     */
    public static function getCurrencyService()
    {
        return self::getService('currency');
    }

    /**
     * @return DateTimeService
     */
    public static function getDateTimeService()
    {
        return self::getService('dateTime');
    }

    /**
     * @return TranslateService
     */
    public static function getTranslateService()
    {
        return self::getService('translate');
    }

    /**
     * @return MessageService
     */
    public static function getMessageService()
    {
        return self::getService('message');
    }

    /**
     * @return SecurityService
     */
    public static function getSecurityService()
    {
        return self::getService('security');
    }

    /**
     * @return RequestService
     */
    public static function getRequestService()
    {
        return self::getService('request');
    }

    /**
     * @return ReportService
     */
    public static function getReportService()
    {
        return self::getService('report');
    }

    /**
     * @return ContractService
     */
    public static function getContractService()
    {
        return self::getService('contract');
    }

    /**
     *
     * @return PdfService
     */
    public static function getPdfService()
    {
        return self::getService('pdf');
    }

    /**
     *
     * @return PaymentService
     */
    public static function getPaymentService()
    {
        return self::getService('payment');
    }

    /**
     * @return AllocationService
     */
    public static function getAllocationService()
    {
        return self::getService('allocation');
    }

    /**
     * @return DocumentService
     */
    public static function getDocumentService()
    {
        return self::getService('document');
    }
}
