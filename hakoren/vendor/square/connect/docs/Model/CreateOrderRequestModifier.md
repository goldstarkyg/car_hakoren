# CreateOrderRequestModifier

## Properties
Name | Getter | Setter | Type | Description | Notes
------------ | ------------- | ------------- | ------------- | ------------- | -------------
**catalog_object_id** | getCatalogObjectId() | setCatalogObjectId($value) | **string** | The catalog object ID of a [CatalogModifier](#type-catalogmodifier). | [optional] 
**name** | getName() | setName($value) | **string** | Only used for ad hoc modifiers. The name of the modifier. &#x60;name&#x60; cannot exceed 255 characters.  Do not provide a value for &#x60;name&#x60; if you provide a value for &#x60;catalog_object_id&#x60;. | [optional] 
**base_price_money** | getBasePriceMoney() | setBasePriceMoney($value) | [**\SquareConnect\Model\Money**](Money.md) | Only used for ad hoc modifiers. The base price for the modifier.  Do not provide a value for &#x60;base_price_money&#x60; if you provide a value for &#x60;catalog_object_id&#x60;. | [optional] 

Note: All properties are protected and only accessed via getters and setters.

[[Back to Model list]](../../README.md#documentation-for-models) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to README]](../../README.md)

