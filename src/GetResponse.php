<?php

/**
 * @package   GetResponse
 * @author    Dario Fumagalli <dario.fumagalli@dftechnosolutions.com>
 * @copyright 2023 DF Techno Solutions
 * @license   GPL 2.0+
 * @link      https://www.dftechnosolutions.com
 *
 * Description:     A Laravel 10+ wrapper for the GetResponse API
 * Version:         1.0.0
 * Author:          Dario Fumagalli
 * Author URI:      https://www.dftechnosolutions.com
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires PHP:    8.1
 */

namespace Dfumagalli\Getresponse;

use Exception;
use Getresponse\Sdk\Client\Exception\InvalidCommandDataException;
use Getresponse\Sdk\Client\Exception\InvalidDomainException;
use Getresponse\Sdk\Client\Exception\MalformedResponseDataException;
use Getresponse\Sdk\Client\GetresponseClient;
use Getresponse\Sdk\Client\Operation\OperationResponse;
use Getresponse\Sdk\Client\Operation\QueryOperation;
use Getresponse\Sdk\Client\Operation\Pagination;
use Getresponse\Sdk\GetresponseClientFactory;
use Getresponse\Sdk\Operation\Autoresponders\CreateAutoresponder\CreateAutoresponder;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponder\GetAutoresponder;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponder\GetAutoresponderFields;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponders\GetAutoresponders;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponders\GetAutorespondersFields;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponders\GetAutorespondersSearchQuery;
use Getresponse\Sdk\Operation\Autoresponders\GetAutoresponders\GetAutorespondersSortParams;
use Getresponse\Sdk\Operation\Campaigns\CreateCampaign\CreateCampaign;
use Getresponse\Sdk\Operation\Contacts\CreateContact\CreateContact;
use Getresponse\Sdk\Operation\Contacts\DeleteContact\DeleteContact;
use Getresponse\Sdk\Operation\Contacts\DeleteContact\DeleteContactUrlQueryParameters;
use Getresponse\Sdk\Operation\Contacts\GetContact\GetContact;
use Getresponse\Sdk\Operation\Contacts\GetContact\GetContactFields;
use Getresponse\Sdk\Operation\Contacts\GetContacts\GetContactsAdditionalFlags;
use Getresponse\Sdk\Operation\Contacts\GetContacts\GetContactsSortParams;
use Getresponse\Sdk\Operation\Contacts\GetContacts\GetContactsSearchQuery;
use Getresponse\Sdk\Operation\Accounts\GetAccounts\GetAccounts;
use Getresponse\Sdk\Operation\Accounts\GetAccounts\GetAccountsFields;
use Getresponse\Sdk\Operation\Campaigns\GetCampaign\GetCampaign;
use Getresponse\Sdk\Operation\Campaigns\GetCampaigns\GetCampaigns;
use Getresponse\Sdk\Operation\Contacts\GetContacts\GetContacts;
use Getresponse\Sdk\Operation\Contacts\GetContacts\GetContactsFields;
use Getresponse\Sdk\Operation\Contacts\UpdateContact\UpdateContact;
use Getresponse\Sdk\Operation\CustomFields\GetCustomField\GetCustomField;
use Getresponse\Sdk\Operation\CustomFields\GetCustomField\GetCustomFieldFields;
use Getresponse\Sdk\Operation\CustomFields\GetCustomFields\GetCustomFieldsFields;
use Getresponse\Sdk\Operation\CustomFields\GetCustomFields\GetCustomFields;
use Getresponse\Sdk\Operation\CustomFields\GetCustomFields\GetCustomFieldsSearchQuery;
use Getresponse\Sdk\Operation\CustomFields\GetCustomFields\GetCustomFieldsSortParams;
use Getresponse\Sdk\Operation\FromFields\GetFromFields\GetFromFields;
use Getresponse\Sdk\Operation\FromFields\GetFromFields\GetFromFieldsFields;
use Getresponse\Sdk\Operation\FromFields\GetFromFields\GetFromFieldsSearchQuery;
use Getresponse\Sdk\Operation\FromFields\GetFromFields\GetFromFieldsSortParams;
use Getresponse\Sdk\Operation\Model\AutoresponderSendSettings;
use Getresponse\Sdk\Operation\Model\CampaignReference;
use Getresponse\Sdk\Operation\Model\FromFieldReference;
use Getresponse\Sdk\Operation\Model\MessageContent;
use Getresponse\Sdk\Operation\Model\MessageEditorEnum;
use Getresponse\Sdk\Operation\Model\MessageFlagsArray;
use Getresponse\Sdk\Operation\Model\NewAutoresponder;
use Getresponse\Sdk\Operation\Model\NewCampaign;
use Getresponse\Sdk\Operation\Model\CampaignOptinTypes;
use Getresponse\Sdk\Operation\Model\CampaignProfile;
use Getresponse\Sdk\Operation\Model\NewContact;
use Getresponse\Sdk\Operation\Model\NewContactCustomFieldValue;
use Getresponse\Sdk\Operation\Model\NewContactTag;
use Getresponse\Sdk\Operation\Model\NewNewsletter;
use Getresponse\Sdk\Operation\Model\NewSearchContacts;
use Getresponse\Sdk\Operation\Model\NewsletterSendSettings;
use Getresponse\Sdk\Operation\Model\NewTag;
use Getresponse\Sdk\Operation\Model\SearchContactConditionsDetails;
use Getresponse\Sdk\Operation\Newsletters\CreateNewsletter\CreateNewsletter;
use Getresponse\Sdk\Operation\Newsletters\DeleteNewsletter\DeleteNewsletter;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletter\GetNewsletter;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletter\GetNewsletterFields;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletters\GetNewsletters;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletters\GetNewslettersFields;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletters\GetNewslettersSearchQuery;
use Getresponse\Sdk\Operation\Newsletters\GetNewsletters\GetNewslettersSortParams;
use Getresponse\Sdk\Operation\Newsletters\Statistics\GetNewsletterStatistics\GetNewsletterStatistics;
use Getresponse\Sdk\Operation\Newsletters\Statistics\GetNewsletterStatistics\GetNewsletterStatisticsFields;
use Getresponse\Sdk\Operation\Newsletters\Statistics\GetNewsletterStatistics\GetNewsletterStatisticsSearchQuery;
use Getresponse\Sdk\Operation\SearchContacts\Contacts\GetContactsBySearchContactsConditions\GetContactsBySearchContactsConditions;
use Getresponse\Sdk\Operation\SearchContacts\CreateSearchContact\CreateSearchContact;
use Getresponse\Sdk\Operation\SearchContacts\DeleteSearchContact\DeleteSearchContact;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContact\GetSearchContact;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContact\GetSearchContactFields;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContacts\GetSearchContacts;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContacts\GetSearchContactsFields;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContacts\GetSearchContactsSearchQuery;
use Getresponse\Sdk\Operation\SearchContacts\GetSearchContacts\GetSearchContactsSortParams;
use Getresponse\Sdk\Operation\Tags\CreateTag\CreateTag;
use Getresponse\Sdk\Operation\Tags\DeleteTag\DeleteTag;
use Getresponse\Sdk\Operation\Tags\GetTag\GetTag;
use Getresponse\Sdk\Operation\Tags\GetTag\GetTagFields;
use Getresponse\Sdk\Operation\Tags\GetTags\GetTags;
use Getresponse\Sdk\Operation\Tags\GetTags\GetTagsFields;
use Getresponse\Sdk\Operation\Tags\GetTags\GetTagsSearchQuery;
use Getresponse\Sdk\Operation\Tags\GetTags\GetTagsSortParams;

class GetResponse
{
    // Factories used to create a GetResponse manager instance

    /**
     * Create a GetResponse instance from the settings stored in the configuration file
     *
     * @return GetResponse
     */
    public static function fromConfig(): GetResponse
    {
        $apiKey                 = config('getresponse.api_key', '');
        $accessToken            = config('getresponse.accessToken', '');
        $useTokenAuthentication = config('getresponse.use_access_token_authentication');
        $isEnterprise           = config('getresponse.is_enterprise');
        $domain                 = config('getresponse.enterprise_domain', '');
        $maxServer              = config('getresponse.max_server', 'US');

        return static::fromParams($apiKey, $accessToken, $useTokenAuthentication, $isEnterprise, $domain, $maxServer);
    }

    /**
     * Create a GetResponse instance from specified parameters
     *
     * @param string $apiKey
     * @param string $accessToken
     * @param bool $useTokenAuthentication
     * @param bool $isEnterprise
     * @param string $domain
     * @param bool $maxServer
     *
     * @return GetResponse
     */
    public static function fromParams(
        string $apiKey,
        string $accessToken,
        bool $useTokenAuthentication,
        bool $isEnterprise,
        string $domain,
        bool $maxServer
    ): GetResponse {
        return new static($apiKey, $accessToken, $useTokenAuthentication, $isEnterprise, $domain, $maxServer);
    }

    /**
     * Create a non enterprise GetResponse instance that uses API key authentication. Used for (unit) testing.
     *
     * @return GetResponse
     */
    public static function forcePersonalAndAPIKey(): GetResponse
    {
        $apiKey = config('getresponse.api_key');

        return new static($apiKey, '', false, false, '', '');
    }

    // Factory used to create a Getresponse REST client instance

    /**
     * Create the appropriate Getresponse client connection
     *
     * @throws InvalidDomainException
     */
    public function newGetresponseClient(): GetresponseClient
    {
        if ($this->isEnterprise) {
            switch ($this->domain) {
                case 'US':
                    if ($this->useTokenAuthentication) {
                        return GetresponseClientFactory::createEnterpriseUSWithAccessToken($this->accessToken, $this->domain);
                    } else {
                        return GetresponseClientFactory::createEnterpriseUSWithApiKey($this->apiKey, $this->domain);
                    }

                    // no break
                case 'PL':
                    if ($this->useTokenAuthentication) {
                        return GetresponseClientFactory::createEnterprisePLWithAccessToken($this->accessToken, $this->domain);
                    } else {
                        return GetresponseClientFactory::createEnterprisePLWithApiKey($this->apiKey, $this->domain);
                    }

                    // no break
                default:
                    throw new InvalidDomainException();
            }
        } else {
            if ($this->useTokenAuthentication) {
                return GetresponseClientFactory::createWithAccessToken($this->accessToken);
            }
        }

        return GetresponseClientFactory::createWithApiKey($this->apiKey);
    }

    // Some common GetResponse functionality. For the full list,
    // @see https://github.com/GetResponse/sdk-php/blob/master/docs

    /**
     * Return true if the GetResponse service can be contacted and queried
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     *
     * @return bool
     */
    public function ping(GetresponseClient $client): bool
    {
        $accountsOperation = new GetAccounts();
        $response = $client->call($accountsOperation);
        return $response->isSuccess();
    }

    /**
     * Get the account data fields. The result can be extracted by @see responseDataAsArray or @see responseDataAsJSON.
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient();
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws InvalidDomainException
     */
    public function getAccounts(GetresponseClient $client, array $fieldsToGet = []): OperationResponse
    {
        if (empty($fieldsToGet)) {
            $fieldsToGet = (new GetAccountsFields())->getAllowedValues();
        }

        $accountsFields = new GetAccountsFields(...$fieldsToGet);

        $accountsOperation = new GetAccounts();
        $accountsOperation->setFields($accountsFields);

        return $client->call($accountsOperation);
    }

    /**
     * Get the list of registered from fields matching the given query and parameters
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetFromFieldsSearchQuery|null $query Optional query, to filter the from fields to fetch
     * @param GetFromFieldsSortParams|null $sort Optional from fields sort order
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     * @param int $fromFieldsPerPage How many from fields rows to fetch per paginated request
     *
     * @return array List of from fields
     * @throws MalformedResponseDataException
     */
    public function getFromFields(
        GetresponseClient $client,
        GetFromFieldsSearchQuery $query = null,
        GetFromFieldsSortParams $sort = null,
        array $fieldsToGet = [],
        int $fromFieldsPerPage = 10
    ): array {
        $getFromFieldsOperation = new GetFromFields();

        if ($query !== null) {
            $getFromFieldsOperation->setQuery($query);
        }

        if ($sort != null) {
            $getFromFieldsOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $fromFields = new GetFromFieldsFields(...$fieldsToGet);
            $getFromFieldsOperation->setFields($fromFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getFromFieldsOperation, $fromFieldsPerPage);
    }

    /**
     * Create a new campaign
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $campaignName Campaign name, Getresponse naming limitations apply
     * @param CampaignProfile $campaignProfile Campaign profile
     * @param CampaignOptinTypes|null $optinTypes Campaign optin types. Null = all optin types are set to 'single'
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function createCampaign(
        GetresponseClient $client,
        string $campaignName,
        CampaignProfile $campaignProfile,
        CampaignOptinTypes $optinTypes = null
    ) {
        $newCampaign = new NewCampaign($campaignName);

        if ($optinTypes === null) {
            $optinTypes = new CampaignOptinTypes();
            $optinTypes->setApi('single');
            $optinTypes->setEmail('single');
            $optinTypes->setImport('single');
            $optinTypes->setWebform('single');
        }

        $newCampaign->setOptinTypes($optinTypes);
        $newCampaign->setProfile($campaignProfile);
        $createCampaignOperation = new CreateCampaign($newCampaign);

        return $client->call($createCampaignOperation);
    }

    /**
     * Get the list of campaigns found on the GetResponse service
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param int $campaignsPerPage How many campaign rows to fetch per paginated request
     *
     * @return array List of campaigns stored on GetResponse
     * @throws MalformedResponseDataException
     */
    public function getCampaigns(GetresponseClient $client, int $campaignsPerPage = 10): array
    {
        $campaignsOperation = new GetCampaigns();

        return $this->responseUnsplitPaginatedDataAsArray($client, $campaignsOperation, $campaignsPerPage);
    }

    /**
     * Get information about a campaign, given its GetResponse id
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $campaignId Campaign id, possibly fetched by @getCampaigns()
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function getCampaign(GetresponseClient $client, string $campaignId): OperationResponse
    {
        $campaignOperation = new GetCampaign($campaignId);

        return $client->call($campaignOperation);
    }

    /**
     * Create a new contact, with optional custom fields and tags
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $campaignId Campaign id, as returned by @getCampaigns()
     * @param string $name Contact name
     * @param string $emailAddress Contact email address
     * @param int|null $dayOfCycle Contact autoresponder day of cycle. Null = not in the cycle
     * @param float|null $scoring Contact scoring. Null = contact with no score.
     * @param string $ipAddress Contact IP address. Must pass a valid, non local address
     * @param array $tagsIds Contact array of tags ids (tags must exist already). Empty array = no tags
     * @param array $customFieldsIdsAndValues Contact array of custom fields. Empty array = no custom fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function createContact(
        GetresponseClient $client,
        string $campaignId,
        string $name,
        string $emailAddress,
        ?int $dayOfCycle = null,
        ?float $scoring = null, // N.B. only supported by advanced GetResponse accounts! If not, error 400 is returned.
        string $ipAddress = '',
        array $tagsIds = [],
        array $customFieldsIdsAndValues = []
    ) {
        $newContact = new NewContact(
            new CampaignReference($campaignId),
            $emailAddress
        );

        $newContact->setName($name);

        if ($dayOfCycle !== null) {
            $newContact->setDayOfCycle($dayOfCycle);
        }

        if ($scoring !== null) {
            $newContact->setScoring($scoring);
        }

        $newContact->setIpAddress($ipAddress);

        if (!empty($tagsIds)) {
            foreach ($tagsIds as $tagId) {
                $tagsCollection[] = new NewContactTag($tagId);
            }

            $newContact->setTags($tagsCollection);
        }

        if (!empty($customFieldsIdsAndValues)) {
            foreach ($customFieldsIdsAndValues as $customFieldsIdsAndValue) {
                $customFieldsCollection[] = new NewContactCustomFieldValue(
                    $customFieldsIdsAndValue['customFieldId'],
                    $customFieldsIdsAndValue['values']
                );
            }

            $newContact->setCustomFieldValues($customFieldsCollection);
        }

        $createContactOperation = new CreateContact($newContact);

        return $client->call($createContactOperation);
    }

    /**
     * Update a contact, given its id, with optional custom fields and tags
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $contactId Contact id, possibly fetched by @getContacts()
     * @param string $campaignId Campaign id, as returned by @getCampaigns(). If not empty, assign to new campaign
     * @param string $name Contact name
     * @param string $emailAddress Contact email address
     * @param int|null $dayOfCycle Contact autoresponder day of cycle. Null = not in the cycle
     * @param float|null $scoring Contact scoring. Null = contact with no score.
     * @param array $tagsIds Contact array of tags ids (tags must exist already). Empty array = no tags
     * @param array $customFieldsIdsAndValues Contact array of custom fields. Empty array = no custom fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function updateContact(
        GetresponseClient $client,
        string $contactId,
        string $campaignId = '',
        string $name = '',
        string $emailAddress = '',
        ?int $dayOfCycle = null,
        ?float $scoring = null, // N.B. only supported by advanced GetResponse accounts! If not, error 400 is returned.
        array $tagsIds = [],
        array $customFieldsIdsAndValues = []
    ) {
        $updateContact = new \Getresponse\Sdk\Operation\Model\UpdateContact();

        if (!empty($campaignId)) {
            $campaignReference = new CampaignReference($campaignId);
            $updateContact->setCampaign($campaignReference);
        }

        if (!empty($name)) {
            $updateContact->setName($name);
        }

        if (!empty($emailAddress)) {
            $updateContact->setEmail($emailAddress);
        }

        if ($dayOfCycle !== null) {
            $updateContact->setDayOfCycle($dayOfCycle);
        }

        if ($scoring !== null) {
            $updateContact->setScoring($scoring);
        }

        if (!empty($tagsIds)) {
            foreach ($tagsIds as $tagId) {
                $tagsCollection[] = new NewContactTag($tagId);
            }

            $updateContact->setTags($tagsCollection);
        }

        if (!empty($customFieldsIdsAndValues)) {
            foreach ($customFieldsIdsAndValues as $customFieldsIdsAndValue) {
                $customFieldsCollection[] = new NewContactCustomFieldValue(
                    $customFieldsIdsAndValue['customFieldId'],
                    $customFieldsIdsAndValue['values']
                );
            }

            $updateContact->setCustomFieldValues($customFieldsCollection);
        }

        $updateContactOperation = new UpdateContact($updateContact, $contactId);
        return $client->call($updateContactOperation);
    }

    /**
     * Delete a contact, given its id, with optional custom URL query parameters
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $contactId Contact id, possibly fetched by @getContacts()
     * @param DeleteContactUrlQueryParameters|null $queryParameters Optional URL query parameters
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function deleteContact(
        GetresponseClient $client,
        string $contactId,
        DeleteContactUrlQueryParameters $queryParameters = null
    ) {
        $deleteContactOperation = new DeleteContact($contactId);

        if ($queryParameters !== null) {
            $deleteContactOperation->setUrlParameterQuery($queryParameters);
        }

        return $client->call($deleteContactOperation);
    }

    /**
     * Get the list of contacts matching the given query and parameters
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetContactsSearchQuery|null $query Optional query, to filter the contacts to fetch
     * @param GetContactsSortParams|null $sort Optional contacts sort order
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     * @param array $additionalFlags Array of additional flags. Pass an empty array to skip the assignment
     * @param int $contactsPerPage How many contact rows to fetch per paginated request
     *
     * @return array List of contacts
     * @throws MalformedResponseDataException
     */
    public function getContacts(
        GetresponseClient $client,
        GetContactsSearchQuery $query = null,
        GetContactsSortParams $sort = null,
        array $fieldsToGet = [],
        array $additionalFlags = [],
        int $contactsPerPage = 10
    ): array {
        $getContactsOperation = new GetContacts();

        if ($query !== null) {
            $getContactsOperation->setQuery($query);
        }

        if ($sort != null) {
            $getContactsOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $contactsFields = new GetContactsFields(...$fieldsToGet);
            $getContactsOperation->setFields($contactsFields);
        }

        if (!empty($additionalFlags)) {
            $getContactsAdditionalFlags = new GetContactsAdditionalFlags(...$additionalFlags);
            $getContactsOperation->setAdditionalFlags($getContactsAdditionalFlags);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getContactsOperation, $contactsPerPage);
    }

    /**
     * Get the list of contacts matching the given query and parameters, one page at a time
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetContactsSearchQuery|null $query Optional query, to filter the contacts to fetch
     * @param GetContactsSortParams|null $sort Optional contacts sort order
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     * @param array $additionalFlags Array of additional flags. Pass an empty array to skip the assignment
     * @param int $contactsPerPage How many contact rows to fetch per paginated request
     * @param int $pageNumber Results page to display
     * @param int $finalPage Data set's last page number, returned by inner pagination calls
     *
     * @return array List of contacts
     * @throws MalformedResponseDataException
     */
    public function getPaginatedContacts(
        GetresponseClient $client,
        GetContactsSearchQuery $query = null,
        GetContactsSortParams $sort = null,
        array $fieldsToGet = [],
        array $additionalFlags = [],
        int $contactsPerPage = 10,
        int $pageNumber = 1,
        int &$finalPage = 1
    ): array {
        $getContactsOperation = new GetContacts();

        if ($query !== null) {
            $getContactsOperation->setQuery($query);
        }

        if ($sort != null) {
            $getContactsOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $contactsFields = new GetContactsFields(...$fieldsToGet);
            $getContactsOperation->setFields($contactsFields);
        }

        if (!empty($additionalFlags)) {
            $getContactsAdditionalFlags = new GetContactsAdditionalFlags(...$additionalFlags);
            $getContactsOperation->setAdditionalFlags($getContactsAdditionalFlags);
        }

        return $this->responsePaginatedDataAsArray(
            $client,
            $getContactsOperation,
            $contactsPerPage,
            $pageNumber,
            $finalPage
        );
    }

    /**
     * Get information about a contact, given its contact id
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $contactId Contact id, possibly fetched by @getContacts()
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function getContact(GetresponseClient $client, string $contactId, array $fieldsToGet = []): OperationResponse
    {
        if (empty($fieldsToGet)) {
            $fieldsToGet = (new GetContactFields())->getAllowedValues();
        }

        $contactFields = new GetContactFields(...$fieldsToGet);

        $contactOperation = new GetContact($contactId);
        $contactOperation->setFields($contactFields);

        return $client->call($contactOperation);
    }

    /**
     * Get the list of custom fields matching the given query and parameters
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetCustomFieldsSearchQuery|null $query Optional query, to filter the custom fields to fetch
     * @param GetCustomFieldsSortParams|null $sort Optional custom fields sort order
     * @param int $fieldsPerPage How many custom fields to fetch per paginated request
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return array List of custom fields stored on GetResponse
     * @throws MalformedResponseDataException
     */
    public function getCustomFields(
        GetresponseClient $client,
        GetCustomFieldsSearchQuery $query = null,
        GetCustomFieldsSortParams $sort = null,
        array $fieldsToGet = [],
        int $fieldsPerPage = 10
    ): array {
        $getCustomFieldsOperation = new GetCustomFields();

        if ($query !== null) {
            $getCustomFieldsOperation->setQuery($query);
        }

        if ($sort != null) {
            $getCustomFieldsOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $getCustomFieldsFields = new GetCustomFieldsFields(...$fieldsToGet);
            $getCustomFieldsOperation->setFields($getCustomFieldsFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getCustomFieldsOperation, $fieldsPerPage);
    }

    /**
     * Get information about a custom field, given its custom field id
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $customFieldId Custom field id, possibly fetched by @getContacts()
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function getCustomField(GetresponseClient $client, string $customFieldId, array $fieldsToGet = []): OperationResponse
    {
        if (empty($fieldsToGet)) {
            $fieldsToGet = (new GetCustomFieldFields())->getAllowedValues();
        }

        $customFieldsFields = new GetCustomFieldFields(...$fieldsToGet);

        $customFieldOperation = new GetCustomField($customFieldId);
        $customFieldOperation->setFields($customFieldsFields);

        return $client->call($customFieldOperation);
    }

    // Search contacts (segments) API calls

    /**
     * Create a new search contacts (segment)
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $name Search contacts (segment) name
     * @param array $section Array of one or more search criteria
     * @param string $sectionLogicOperator Include contacts that match the criteria above, combined by this operator
     * @param array $subscribersType Only include contacts with this subscription status
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function createSearchContact(
        GetresponseClient $client,
        NewSearchContacts $newSearchContacts
    ) {
        $createContactOperation = new CreateSearchContact($newSearchContacts);

        return $client->call($createContactOperation);
    }

    /**
     * Get the saved search contacts (segments) matching the given query and parameters, one page at a time
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetContactsSearchQuery|null $query Optional query, to filter the saved search contacts to fetch
     * @param GetContactsSortParams|null $sort Optional saved search contacts sort order
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     * @param int $searchContactsPerPage How many saved search contacts rows to fetch per paginated request
     * @param int $pageNumber Results page to display
     * @param int $finalPage Data set's last page number, returned by inner pagination calls
     *
     * @return array List of saved search contacts
     * @throws MalformedResponseDataException
     */
    public function getPaginatedSearchContacts(
        GetresponseClient $client,
        GetSearchContactsSearchQuery $query = null,
        GetSearchContactsSortParams $sort = null,
        array $fieldsToGet = [],
        int $searchContactsPerPage = 10,
        int $pageNumber = 1,
        int &$finalPage = 1
    ): array {
        $getSearchContactOperation = new GetSearchContacts();

        if ($query !== null) {
            $getSearchContactOperation->setQuery($query);
        }

        if ($sort != null) {
            $getSearchContactOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $searchContactsFields = new GetSearchContactsFields(...$fieldsToGet);
            $getSearchContactOperation->setFields($searchContactsFields);
        }

        return $this->responsePaginatedDataAsArray(
            $client,
            $getSearchContactOperation,
            $searchContactsPerPage,
            $pageNumber,
            $finalPage
        );
    }

    /**
     * Get information about a saved search contacts (segments), given its saved search contacts id
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $searchContactId Saved search contacts id, possibly fetched by @getSearchContacts()
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function getSearchContact(GetresponseClient $client, string $searchContactId, array $fieldsToGet = []): OperationResponse
    {
        if (empty($fieldsToGet)) {
            $fieldsToGet = (new GetSearchContactFields())->getAllowedValues();
        }

        $tagFields = new GetSearchContactFields(...$fieldsToGet);

        $getSearchContactOperation = new GetSearchContact($searchContactId);
        $getSearchContactOperation->setFields($tagFields);

        return $client->call($getSearchContactOperation);
    }

    /**
     * Delete a saved search contacts (segments) given its id
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $searchContactId
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function deleteSearchContact(
        GetresponseClient $client,
        string $searchContactId
    ) {
        $deleteSearchContactOperation = new DeleteSearchContact($searchContactId);
        return $client->call($deleteSearchContactOperation);
    }

    // Tag API calls

    /**
     * Create a new tag
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $name Tag name
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function createTag(
        GetresponseClient $client,
        string $name
    ) {
        $newTag = new NewTag($name);

        $createTagOperation = new CreateTag($newTag);
        return $client->call($createTagOperation);
    }

    /**
     * Get the list of tags matching the given query and parameters
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetTagsSearchQuery|null $query Optional query, to filter the custom fields to fetch
     * @param GetTagsSortParams|null $sort Optional custom fields sort order
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     * @param int $fieldsPerPage How many custom fields to fetch per paginated request
     *
     * @return array List of custom fields stored on GetResponse
     * @throws MalformedResponseDataException
     */
    public function getTags(
        GetresponseClient $client,
        GetTagsSearchQuery $query = null,
        GetTagsSortParams $sort = null,
        array $fieldsToGet = [],
        int $fieldsPerPage = 10
    ): array {
        $getTagsOperation = new GetTags();

        if ($query !== null) {
            $getTagsOperation->setQuery($query);
        }

        if ($sort != null) {
            $getTagsOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $tagsFields = new GetTagsFields(...$fieldsToGet);
            $getTagsOperation->setFields($tagsFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getTagsOperation, $fieldsPerPage);
    }

    /**
     * Get information about a tag, given its tag id
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $tagId Tag id, possibly fetched by @getTags()
     * @param array $fieldsToGet Array of fields names to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function getTag(GetresponseClient $client, string $tagId, array $fieldsToGet = []): OperationResponse
    {
        if (empty($fieldsToGet)) {
            $fieldsToGet = (new GetTagFields())->getAllowedValues();
        }

        $tagFields = new GetTagFields(...$fieldsToGet);

        $getTagOperation = new GetTag($tagId);
        $getTagOperation->setFields($tagFields);

        return $client->call($getTagOperation);
    }

    /**
     * Delete a tag given its id
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $tagId Tag id
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function deleteTag(
        GetresponseClient $client,
        string $tagId
    ) {
        $deleteTagOperation = new DeleteTag($tagId);
        return $client->call($deleteTagOperation);
    }

    // Newsletters API calls

    /**
     * Create a new newsletter, with optional custom fields and tags.
     * Reference at @see https://apireference.getresponse.com/#operation/createNewsletter
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $campaignId Campaign id the newsletter is attached to, as returned by @getCampaigns()
     * @param string $name Newsletter name, as shown on GetResponse's newsletter composer
     * @param string $subject Newsletter (email) subject
     * @param string $contactFromId Id of the contact sending the newsletter
     * @param string $contactReplyToId Id of the reply to contact. Empty = same as $contactFromId
     * @param string $plainContent Plain text version of the newsletter (max 500k)
     * @param string $htmlContent HTML version of the newsletter (max 500k)
     * @param MessageFlagsArray|null $messageFlags Statistics to gather. Defaults to "openrate"  if null
     * @param NewsletterSendSettings|null $newsletterSendSettings Destination contacts list source(s)
     * @param string $sendOn When to send the newsletter. Empty = send now.
     * @param array $newsletterAttachments Newsletter attachments (max 400KB for all the attachments combined)
     * @param MessageEditorEnum|null $messageEditor How the message was created. Empty = "custom".
     * @param string $type Newsletter type. Empty = "broadcast".
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws InvalidCommandDataException
     */
    public function createNewsletter(
        GetresponseClient $client,
        string $campaignId,
        string $name,
        string $subject,
        string $contactFromId,
        string $contactReplyToId = '',
        string $plainContent = '',
        string $htmlContent = '',
        MessageFlagsArray $messageFlags = null,
        NewsletterSendSettings $newsletterSendSettings = null,
        string $sendOn = '',
        array $newsletterAttachments = [],
        MessageEditorEnum $messageEditor = null,
        string $type = ''
    ) {
        $createNewsletterContent = new MessageContent();

        if (!empty($plainContent)) {
            $createNewsletterContent->setPlain($plainContent);
        }

        if (!empty($htmlContent)) {
            $createNewsletterContent->setHtml($htmlContent);
        }

        $createNewsletterSendSettings = $newsletterSendSettings ?? new NewsletterSendSettings();

        $createNewsletter = new NewNewsletter(
            $subject,
            new FromFieldReference($contactFromId),
            new CampaignReference($campaignId),
            $createNewsletterContent,
            $createNewsletterSendSettings
        );

        $createNewsletter->setName($name);

        if (!$messageFlags) {
            $messageFlags = new MessageFlagsArray('openrate');
        }

        $createNewsletter->setFlags($messageFlags);

        if (empty($contactReplyToId)) {
            $contactReplyToId = $contactFromId;
        }

        $createNewsletter->setReplyTo(new FromFieldReference($contactReplyToId));

        if (!empty($newsletterAttachments)) {
            $createNewsletter->setAttachments($newsletterAttachments);
        }

        if (!$messageEditor) {
            $messageEditor = new MessageEditorEnum('custom');
        }

        $createNewsletter->setEditor($messageEditor);

        if (!empty($sendOn)) {
            $createNewsletter->setSendOn($sendOn);
        }

        if (empty($type)) {
            $type = 'broadcast';
        }

        $createNewsletter->setType($type);

        $createNewsletterOperation = new CreateNewsletter($createNewsletter);

        /*
            $test = var_export($createNewsletterOperation->getMethod(), true);
            $test .= "\n"  . var_export($createNewsletterOperation->getUrl(), true);
            $test .= "\n"  . var_export($createNewsletterOperation->getBody(), true);
        */

        return $client->call($createNewsletterOperation);
    }

    /**
     * Get the list of newsletters matching the given query and parameters
     * Reference at @see https://apireference.getresponse.com/#operation/getNewsletterList
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetNewslettersSearchQuery|null $query Optional query, to filter the newsletters to fetch
     * @param GetNewslettersSortParams|null $sort Optional newsletters sort order
     * @param array $fieldsToGet Array of newsletters fields to get. Pass an empty array to fetch all the fields
     * @param int $newslettersPerPage How many newsletters rows to fetch per paginated request
     *
     * @return array List of newsletters
     * @throws MalformedResponseDataException
     */
    public function getNewsletters(
        GetresponseClient $client,
        GetNewslettersSearchQuery $query = null,
        GetNewslettersSortParams $sort = null,
        array $fieldsToGet = [],
        int $newslettersPerPage = 10
    ): array {
        $getNewslettersOperation = new GetNewsletters();

        if ($query !== null) {
            $getNewslettersOperation->setQuery($query);
        }

        if ($sort != null) {
            $getNewslettersOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $newslettersFields = new GetNewslettersFields(...$fieldsToGet);
            $getNewslettersOperation->setFields($newslettersFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getNewslettersOperation, $newslettersPerPage);
    }

    /**
     * Get the details of a newsletter given its id
     * Reference at @see https://apireference.getresponse.com/#operation/getNewsletter
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $newsletterId Id of the newsletter to get the details of
     * @param array $fieldsToGet Array of newsletter details fields to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function getNewsletter(
        GetresponseClient $client,
        string $newsletterId,
        array $fieldsToGet = []
    ): OperationResponse {
        $getNewsletterOperation = new GetNewsletter($newsletterId);

        if (!empty($fieldsToGet)) {
            $newsletterFields = new GetNewsletterFields(...$fieldsToGet);
            $getNewsletterOperation->setFields($newsletterFields);
        }

        return $client->call($getNewsletterOperation);
    }

    /**
     * Delete a newsletter given its id
     * Reference at @see https://apireference.getresponse.com/#operation/deleteNewsletter
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $newsletterId Id of the newsletter to delete
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function deleteNewsletter(
        GetresponseClient $client,
        string $newsletterId,
    ): OperationResponse {
        $deleteNewsletterOperation = new DeleteNewsletter($newsletterId);

        return $client->call($deleteNewsletterOperation);
    }

    /**
     * Get a specific newsletters statistics
     * Reference at @see https://apireference.getresponse.com/#operation/getSingleNewsletterStatistics
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $newsletterId Id of the newsletter to get the statistics of
     * @param GetNewsletterStatisticsSearchQuery|null $query Optional query, to filter the newsletters statistics to fetch
     * @param array $fieldsToGet Array of newsletters statistics fields to get. Pass an empty array to fetch all the fields
     *
     * @return array Newsletter statistics
     * @throws MalformedResponseDataException
     */
    public function getNewsletterStatistics(
        GetresponseClient $client,
        string $newsletterId,
        GetNewsletterStatisticsSearchQuery $query = null,
        array $fieldsToGet = [],
    ): array {
        $getNewsletterStatisticsOperation = new GetNewsletterStatistics($newsletterId);

        if ($query !== null) {
            $getNewsletterStatisticsOperation->setQuery($query);
        }

        if (!empty($fieldsToGet)) {
            $newsletterStatisticsFields = new GetNewsletterStatisticsFields(...$fieldsToGet);
            $getNewsletterStatisticsOperation->setFields($newsletterStatisticsFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getNewsletterStatisticsOperation, 100);
    }

    // Autoresponders API calls

    /**
     * Create a new autoresponder, with optional custom fields and tags.
     * Reference at @see https://apireference.getresponse.com/#operation/createAutoresponder
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $campaignId Campaign id the autoresponder is attached to, as returned by @getCampaigns()
     * @param string $name Autoresponder name, as shown on GetResponse's autoresponder composer
     * @param string $subject Autoresponder (email) subject
     * @param string $contactFromId Id of the contact sending the autoresponder
     * @param string $contactReplyToId Id of the reply to contact. Empty = same as $contactFromId
     * @param string $plainContent Plain text version of the autoresponder (max 500k)
     * @param string $htmlContent HTML version of the autoresponder (max 500k)
     * @param MessageFlagsArray|null $messageFlags Statistics to gather. Defaults to "openrate"  if null
     * @param AutoresponderSendSettings|null $newsletterSendSettings Destination contacts list source(s)
     * @param string $sendOn When to send the autoresponder. Empty = send now.
     * @param array $newsletterAttachments Autoresponder attachments (max 400KB for all the attachments combined)
     * @param MessageEditorEnum|null $messageEditor How the message was created. Empty = "custom".
     * @param string $type Autoresponder type. Empty = "broadcast".
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     */
    public function createAutoresponder(
        GetresponseClient $client,
        string $campaignId,
        string $name,
        string $subject,
        string $contactFromId,
        string $contactReplyToId = '',
        string $plainContent = '',
        string $htmlContent = '',
        MessageFlagsArray $messageFlags = null,
        AutoresponderSendSettings $newsletterSendSettings = null,
        string $sendOn = '',
        array $newsletterAttachments = [],
        MessageEditorEnum $messageEditor = null,
        string $type = ''
    ) {
        // TODO This code is NOT complete!
        $createAutoresponderContent = new MessageContent();

        if (!empty($plainContent)) {
            $createAutoresponderContent->setPlain($plainContent);
        }

        if (!empty($htmlContent)) {
            $createAutoresponderContent->setHtml($htmlContent);
        }

        $createAutoresponderSendSettings = $newsletterSendSettings ?? new NewsletterSendSettings();

        $createAutoresponder = new NewAutoresponder(
            $subject,
            new FromFieldReference($contactFromId),
            new CampaignReference($campaignId),
            $createAutoresponderContent,
            $createAutoresponderSendSettings
        );

        $createAutoresponder->setName($name);

        if (!$messageFlags) {
            $messageFlags = new MessageFlagsArray('openrate');
        }

        $createAutoresponder->setFlags($messageFlags);

        if (empty($contactReplyToId)) {
            $contactReplyToId = $contactFromId;
        }

        $createAutoresponder->setReplyTo(new FromFieldReference($contactReplyToId));

        if (!empty($newsletterAttachments)) {
            $createAutoresponder->setAttachments($newsletterAttachments);
        }

        if (!$messageEditor) {
            $messageEditor = new MessageEditorEnum('custom');
        }

        $createAutoresponder->setEditor($messageEditor);

        if (!empty($sendOn)) {
            $createAutoresponder->setSendOn($sendOn);
        }

        if (empty($type)) {
            $type = 'broadcast';
        }

        $createAutoresponder->setType($type);

        $createAutoresponderOperation = new CreateAutoresponder($createAutoresponder);

        /*
            $test = var_export($createAutoresponderOperation->getMethod(), true);
            $test .= "\n"  . var_export($createAutoresponderOperation->getUrl(), true);
            $test .= "\n"  . var_export($createAutoresponderOperation->getBody(), true);
        */

        return $client->call($createAutoresponderOperation);
    }

    /**
     * Get the list of autoresponders matching the given query and parameters
     * Reference at @see https://apireference.getresponse.com/#operation/getAutoresponderList
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param GetAutorespondersSearchQuery|null $query Optional query, to filter the autoresponders to fetch
     * @param GetAutorespondersSortParams|null $sort Optional autoresponders sort order
     * @param array $fieldsToGet Array of autoresponders fields to get. Pass an empty array to fetch all the fields
     * @param int $autorespondersPerPage How many autoresponders rows to fetch per paginated request
     *
     * @return array List of autoresponder
     * @throws MalformedResponseDataException
     */
    public function getAutoresponders(
        GetresponseClient $client,
        GetAutorespondersSearchQuery $query = null,
        GetAutorespondersSortParams $sort = null,
        array $fieldsToGet = [],
        int $autorespondersPerPage = 10
    ): array {
        $getAutorespondersOperation = new GetAutoresponders();

        if ($query !== null) {
            $getAutorespondersOperation->setQuery($query);
        }

        if ($sort != null) {
            $getAutorespondersOperation->setSort($sort);
        }

        if (!empty($fieldsToGet)) {
            $autorespondersFields = new GetAutorespondersFields(...$fieldsToGet);
            $getAutorespondersOperation->setFields($autorespondersFields);
        }

        return $this->responseUnsplitPaginatedDataAsArray($client, $getAutorespondersOperation, $autorespondersPerPage);
    }

    /**
     * Get the details of an autoresponder given its id
     * Reference at @see https://apireference.getresponse.com/#operation/getAutoresponder
     *
     * @param GetresponseClient $client $client Getresponse client instance, created by @newGetresponseClient()
     * @param string $autoresponderId Id of the autoresponder to get the details of
     * @param array $fieldsToGet Array of autoresponder details fields to get. Pass an empty array to fetch all the fields
     *
     * @return OperationResponse Response object, it can be unpacked by @responseDataAsArray or @responseDataAsJSON
     * @throws MalformedResponseDataException
     */
    public function getAutoresponder(
        GetresponseClient $client,
        string $autoresponderId,
        array $fieldsToGet = []
    ): OperationResponse {
        $getAutoresponderOperation = new GetAutoresponder($autoresponderId);

        if (!empty($fieldsToGet)) {
            $autoresponderFields = new GetAutoresponderFields(...$fieldsToGet);
            $getAutoresponderOperation->setFields($autoresponderFields);
        }

        return $client->call($getAutoresponderOperation);
    }

    // Returned / response data manipulation functions. Data may be manipulated either as array or as JSON (never
    // both, because they share and fetch from a stream, and it gets used up).

    /**
     * Return REST call response data as array. N.B. cannot be called after a previous call to @responseDataAsJSON!
     *
     * @param OperationResponse $response
     *
     * @return array
     *
     * @throws MalformedResponseDataException
     */
    public function responseDataAsArray(OperationResponse $response): array
    {
        return $response->getData();
    }

    /**
     * Return REST call response data as JSON. N.B. cannot be called after a previous call to @responseDataAsArray!
     *
     * @param OperationResponse $response
     *
     * @return string
     */
    public function responseDataAsJSON(OperationResponse $response): string
    {
        return $response->getResponse()->getBody()->getContents();
    }

    /**
     * Unsplit and return paginated data as one big array. Especially useful when returning lists of items
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param QueryOperation $clientOperation GetResponse operation { @see https://github.com/GetResponse/sdk-php/blob/master/docs }
     * @param int $resultsPerPage How many items to return per each page
     *
     * @return array Returned list of items
     * @throws MalformedResponseDataException
     * @throws Exception
     */
    public function responseUnsplitPaginatedDataAsArray(
        GetresponseClient $client,
        QueryOperation $clientOperation,
        int $resultsPerPage = 10
    ): array {
        $pageNumber = 1;
        $finalPage = 1;
        $results = [];

        while ($pageNumber <= $finalPage) {
            $subResults = $this->responsePaginatedDataAsArray(
                $client,
                $clientOperation,
                $resultsPerPage,
                $pageNumber, // Passed by reference, because the value can change in rel time
                $finalPage   // Passed by reference, because the value can change in rel time
            );

            foreach ($subResults as $subResult) {
                $results[] = $subResult;
            }
        }

        return $results;
    }

    /**
     * Return one page of paginated data as array. Especially useful when returning lists of items
     *
     * @param GetresponseClient $client Getresponse client instance, created by @newGetresponseClient()
     * @param QueryOperation $clientOperation GetResponse operation { @see https://github.com/GetResponse/sdk-php/blob/master/docs }
     * @param int $resultsPerPage How many items to return per each page
     * @param int $pageNumber Results page to display
     * @param int $finalPage Data set's last page number, returned by the REST call
     *
     * @return array Returned list of items
     * @throws MalformedResponseDataException
     * @throws Exception
     */
    public function responsePaginatedDataAsArray(
        GetresponseClient $client,
        QueryOperation $clientOperation,
        int $resultsPerPage = 10,
        int &$pageNumber = 1,
        int &$finalPage = 1
    ): array {
        /**
         * There could be pagination, so we have to send requests for each page
         */
        $results = [];

        $clientOperation->setPagination(new Pagination($pageNumber, $resultsPerPage));

        $response = $client->call($clientOperation);

        if ($response->isSuccess()) {
            /**
             * Note: as operations are asynchronous, pagination data could change during the execution
             * of this code, so it is better to adjust finalPage every call
             */
            if ($response->isPaginated()) {
                $paginationValues = $response->getPaginationValues();
                $finalPage = $paginationValues->getTotalPages();
            }

            $responseDataArray = $response->getData();
            foreach ($responseDataArray as $responseDataRow) {
                $results[] = $responseDataRow;
                // var_dump($responseDataRow);
            }

            $pageNumber++;
        } else {
            $errorData = $response->getData();
            throw new Exception(
                __('Error fetching data from the mailing list service:' . ' ' . $errorData['message'])
            );
        }

        return $results;
    }

    protected function __construct(
        public string $apiKey,
        public string $accessToken,
        public bool $useTokenAuthentication,
        public bool $isEnterprise,
        public string $domain,
        public bool $maxServer
    ) {
        //
    }
}
