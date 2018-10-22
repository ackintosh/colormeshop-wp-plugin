<?php
/**
 * ProductVariant
 *
 * PHP version 5
 *
 * @category Class
 * @package  ColorMeShop\Swagger
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * カラーミーショップ API
 *
 * # カラーミーショップ API  [カラーミーショップ](https://shop-pro.jp) APIでは、受注の検索や商品情報の更新を行うことができます。  ## 利用手順  はじめに、カラーミーデベロッパーアカウントを用意します。[デベロッパー登録ページ](https://api.shop-pro.jp/developers/sign_up)から登録してください。  次に、[登録ページ](https://api.shop-pro.jp/oauth/applications/new)からアプリケーション登録を行ってください。 スマートフォンのWebViewを利用する場合は、リダイレクトURIに`urn:ietf:wg:oauth:2.0:oob`を入力してください。  その後、カラーミーショップアカウントの認証ページを開きます。認証ページのURLは、`https://api.shop-pro.jp/oauth/authorize`に必要なパラメータをつけたものです。  |パラメータ名|値| |---|---| |`client_id`|アプリケーション詳細画面で確認できるクライアントID| |`response_type`|\"code\"という文字列| |`scope`| 別表参照| |`redirect_uri`|アプリケーション登録時に入力したリダイレクトURI|  `scope`は、以下のうち、アプリケーションが利用したい機能をスペース区切りで指定してください。  |スコープ|機能| |---|---| |`read_products`|商品データの参照| |`write_products`|在庫データの更新| |`read_sales`|受注・顧客データの参照| |`write_sales`|受注データの更新|  以下のようなURLとなります。  ``` https://api.shop-pro.jp/oauth/authorize?client_id=CLIENT_ID&redirect_uri=REDIRECT_URI&response_type=code&scope=read_products%20write_products ```  初めてこのページを訪れる場合は、カラーミーショップアカウントのIDとパスワードの入力を求められます。 承認ボタンを押すと、このアプリケーションがショップのデータにアクセスすることが許可され、リダイレクトURIへリダイレクトされます。  承認された場合は、`code`というクエリパラメータに認可コードが付与されます。承認がキャンセルされた、またはエラーが起きた場合は、 `error`パラメータにエラーの内容を表す文字列が与えられます。  アプリケーション登録時のリダイレクトURIに`urn:ietf:wg:oauth:2.0:oob`を指定した場合は、以下のようなURLにリダイレクトされます。 末尾のパスが認可コードになっています。  ``` https://api.shop-pro.jp/oauth/authorize/AUTH_CODE ```  認可コードの有効期限は発行から10分間です。  最後に、認可コードとアクセストークンを交換します。以下のパラメータを付けて、`https://api.shop-pro.jp/oauth/token`へリクエストを送ります。  |パラメータ名|値| |---|---| |`client_id`|アプリケーション詳細画面に表示されているクライアントID| |`client_secret`|アプリケーション詳細画面に表示されているクライアントシークレット| |`code`|取得した認可コード| |`grant_type`|\"authorization_code\"という文字列| |`redirect_uri`|アプリケーション登録時に入力したリダイレクトURI|  ```console # curl での例  $ curl -X POST \\   -d'client_id=CLIENT_ID' \\   -d'client_secret=CLIENT_SECRET' \\   -d'code=CODE' \\   -d'grant_type=authorization_code'   \\   -d'redirect_uri=REDIRECT_URI'  \\   'https://api.shop-pro.jp/oauth/token' ```  リクエストが成功すると、以下のようなJSONが返ってきます。  ```json {   \"access_token\": \"d461ab8XXXXXXXXXXXXXXXXXXXXXXXXX\",   \"token_type\": \"bearer\",   \"scope\": \"read_products write_products\" } ```  アクセストークンに有効期限はありませんが、許可済みアプリケーション一覧画面から失効させることができます。なお、同じ認可コードをアクセストークンに交換できるのは1度だけです。  取得したアクセストークンは、Authorizationヘッダに入れて使用します。以下にショップ情報を取得する際の例を示します。  ```console # curlの例  $ curl -H 'Authorization: Bearer d461ab8XXXXXXXXXXXXXXXXXXXXXXXXX' https://api.shop-pro.jp/v1/shop.json ```  ## エラー  カラーミーショップAPI v1では  - エラーコード - エラーメッセージ - ステータスコード  の配列でエラーを表現します。以下に例を示します。  ```json {   \"errors\": [     {       \"code\": 404100,       \"message\": \"レコードが見つかりませんでした。\",       \"status\": 404     }   ] } ```
 *
 * OpenAPI spec version: 1.0.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.3.0
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace ColorMeShop\Swagger\Model;

use \ArrayAccess;
use \ColorMeShop\Swagger\ObjectSerializer;

/**
 * ProductVariant Class Doc Comment
 *
 * @category Class
 * @package  ColorMeShop\Swagger
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class ProductVariant implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'ProductVariant';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'product_id' => 'int',
        'account_id' => 'string',
        'option1_value' => 'string',
        'option2_value' => 'string',
        'title' => 'string',
        'stocks' => 'int',
        'few_num' => 'int',
        'model_number' => 'string',
        'option_price' => 'int',
        'option_members_price' => 'int',
        'make_date' => 'int',
        'update_date' => 'int'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'product_id' => null,
        'account_id' => null,
        'option1_value' => null,
        'option2_value' => null,
        'title' => null,
        'stocks' => null,
        'few_num' => null,
        'model_number' => null,
        'option_price' => null,
        'option_members_price' => null,
        'make_date' => null,
        'update_date' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'product_id' => 'product_id',
        'account_id' => 'account_id',
        'option1_value' => 'option1_value',
        'option2_value' => 'option2_value',
        'title' => 'title',
        'stocks' => 'stocks',
        'few_num' => 'few_num',
        'model_number' => 'model_number',
        'option_price' => 'option_price',
        'option_members_price' => 'option_members_price',
        'make_date' => 'make_date',
        'update_date' => 'update_date'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'product_id' => 'setProductId',
        'account_id' => 'setAccountId',
        'option1_value' => 'setOption1Value',
        'option2_value' => 'setOption2Value',
        'title' => 'setTitle',
        'stocks' => 'setStocks',
        'few_num' => 'setFewNum',
        'model_number' => 'setModelNumber',
        'option_price' => 'setOptionPrice',
        'option_members_price' => 'setOptionMembersPrice',
        'make_date' => 'setMakeDate',
        'update_date' => 'setUpdateDate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'product_id' => 'getProductId',
        'account_id' => 'getAccountId',
        'option1_value' => 'getOption1Value',
        'option2_value' => 'getOption2Value',
        'title' => 'getTitle',
        'stocks' => 'getStocks',
        'few_num' => 'getFewNum',
        'model_number' => 'getModelNumber',
        'option_price' => 'getOptionPrice',
        'option_members_price' => 'getOptionMembersPrice',
        'make_date' => 'getMakeDate',
        'update_date' => 'getUpdateDate'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['product_id'] = isset($data['product_id']) ? $data['product_id'] : null;
        $this->container['account_id'] = isset($data['account_id']) ? $data['account_id'] : null;
        $this->container['option1_value'] = isset($data['option1_value']) ? $data['option1_value'] : null;
        $this->container['option2_value'] = isset($data['option2_value']) ? $data['option2_value'] : null;
        $this->container['title'] = isset($data['title']) ? $data['title'] : null;
        $this->container['stocks'] = isset($data['stocks']) ? $data['stocks'] : null;
        $this->container['few_num'] = isset($data['few_num']) ? $data['few_num'] : null;
        $this->container['model_number'] = isset($data['model_number']) ? $data['model_number'] : null;
        $this->container['option_price'] = isset($data['option_price']) ? $data['option_price'] : null;
        $this->container['option_members_price'] = isset($data['option_members_price']) ? $data['option_members_price'] : null;
        $this->container['make_date'] = isset($data['make_date']) ? $data['make_date'] : null;
        $this->container['update_date'] = isset($data['update_date']) ? $data['update_date'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        return true;
    }


    /**
     * Gets product_id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->container['product_id'];
    }

    /**
     * Sets product_id
     *
     * @param int $product_id 商品ID
     *
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->container['product_id'] = $product_id;

        return $this;
    }

    /**
     * Gets account_id
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->container['account_id'];
    }

    /**
     * Sets account_id
     *
     * @param string $account_id ショップアカウントID
     *
     * @return $this
     */
    public function setAccountId($account_id)
    {
        $this->container['account_id'] = $account_id;

        return $this;
    }

    /**
     * Gets option1_value
     *
     * @return string
     */
    public function getOption1Value()
    {
        return $this->container['option1_value'];
    }

    /**
     * Sets option1_value
     *
     * @param string $option1_value オプション1の値
     *
     * @return $this
     */
    public function setOption1Value($option1_value)
    {
        $this->container['option1_value'] = $option1_value;

        return $this;
    }

    /**
     * Gets option2_value
     *
     * @return string
     */
    public function getOption2Value()
    {
        return $this->container['option2_value'];
    }

    /**
     * Sets option2_value
     *
     * @param string $option2_value オプション2の値
     *
     * @return $this
     */
    public function setOption2Value($option2_value)
    {
        $this->container['option2_value'] = $option2_value;

        return $this;
    }

    /**
     * Gets title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->container['title'];
    }

    /**
     * Sets title
     *
     * @param string $title オプション1とオプション2の名前を\"　x　\"で結合した表示名。オプションが1つしか設定されていない場合はそのオプションの名前に等しい
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->container['title'] = $title;

        return $this;
    }

    /**
     * Gets stocks
     *
     * @return int
     */
    public function getStocks()
    {
        return $this->container['stocks'];
    }

    /**
     * Sets stocks
     *
     * @param int $stocks 在庫数
     *
     * @return $this
     */
    public function setStocks($stocks)
    {
        $this->container['stocks'] = $stocks;

        return $this;
    }

    /**
     * Gets few_num
     *
     * @return int
     */
    public function getFewNum()
    {
        return $this->container['few_num'];
    }

    /**
     * Sets few_num
     *
     * @param int $few_num 残りわずかとなる在庫数
     *
     * @return $this
     */
    public function setFewNum($few_num)
    {
        $this->container['few_num'] = $few_num;

        return $this;
    }

    /**
     * Gets model_number
     *
     * @return string
     */
    public function getModelNumber()
    {
        return $this->container['model_number'];
    }

    /**
     * Sets model_number
     *
     * @param string $model_number 型番
     *
     * @return $this
     */
    public function setModelNumber($model_number)
    {
        $this->container['model_number'] = $model_number;

        return $this;
    }

    /**
     * Gets option_price
     *
     * @return int
     */
    public function getOptionPrice()
    {
        return $this->container['option_price'];
    }

    /**
     * Sets option_price
     *
     * @param int $option_price 販売価格
     *
     * @return $this
     */
    public function setOptionPrice($option_price)
    {
        $this->container['option_price'] = $option_price;

        return $this;
    }

    /**
     * Gets option_members_price
     *
     * @return int
     */
    public function getOptionMembersPrice()
    {
        return $this->container['option_members_price'];
    }

    /**
     * Sets option_members_price
     *
     * @param int $option_members_price 会員価格
     *
     * @return $this
     */
    public function setOptionMembersPrice($option_members_price)
    {
        $this->container['option_members_price'] = $option_members_price;

        return $this;
    }

    /**
     * Gets make_date
     *
     * @return int
     */
    public function getMakeDate()
    {
        return $this->container['make_date'];
    }

    /**
     * Sets make_date
     *
     * @param int $make_date オプション作成日時
     *
     * @return $this
     */
    public function setMakeDate($make_date)
    {
        $this->container['make_date'] = $make_date;

        return $this;
    }

    /**
     * Gets update_date
     *
     * @return int
     */
    public function getUpdateDate()
    {
        return $this->container['update_date'];
    }

    /**
     * Sets update_date
     *
     * @param int $update_date オプション更新日時
     *
     * @return $this
     */
    public function setUpdateDate($update_date)
    {
        $this->container['update_date'] = $update_date;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


