<p align="center">
<a href="https://docparser.com" title="Extract Data From PDF"><img width="280" src="https://docparser.com/img/logo.png"></a>
</p>

<h2 align="center">Official Docparser API PHP Client</h2>

<p align="center"><code>docparser-php</code> provides convenient PHP bindings for the <a href="https://dev.docparser.com">Docparser API</a>.</p>

<hr>

<p align="center">
<b><a href="#documentation">Documentation</a></b>
|
<b><a href="#installation">Installation</a></b>
|
<b><a href="#configuration">Configuration</a></b>
|
<b><a href="#usage">Usage</a></b>
|
<b><a href="#contributing">Contributing</a></b>
|
<b><a href="#license">License</a></b>
|
<b><a href="#changelog">Changelog</a></b>
</p>
<hr>
<br>

## Documentation

For a generic description of the Docparser API, please see our developer documentation [here](https://dev.docparser.com/). Our developer documentation lists all available API methods with their parameters and expected responses.

## Installation

This library requires php 5.5 or above.


**Using Composer**:


```sh
composer require docparser/docparser-php
```
or in `composer.json`:

```json
{
    "require": {
        "docparser/docparser-php": "1.*"
    }
}
```

## Configuration

Create a Docparser PHP Client by using your Docparser API Token:

```php
require('./vendor/autoload.php');

use Docparser\Docparser;
$docparser = new Docparser("APITOKEN");
```

### Test Your Authentication

You can call our `ping()` method to test your API key. The method retuns a boolean value which indicates if a connection to our API could be established and that the API key is working.

```php
echo $docparser->ping();
```
## Usage

### [Document Parsers](https://dev.docparser.com/#parsers)

**List All Document Parsers**

Returns a list of the document parsers created in your account.
```php
$docparser->getParsers();
```

### [Documents](https://dev.docparser.com/#documents)

The Docparser PHP SDK offers three different methods for importing your document.

All import methods allow you to pass a `$remoteId` with your document. The remote ID can be any arbitrary string with a maximum length of 255 characters. The submitted value will be kept throughout the processing and will be available later once you obtain the parsed data with our API or through Webhooks.

**Upload Document From Local File System**

Reads a file from your local filesystem and uploads it to your document parser.
```php
$docparser->uploadDocumentByPath($parserId, $filePath, $remoteId = null);
```

**Upload Document By Providing File Content**

This method creates a new document in your document parser based on the raw file content or a file pointer. Additionally, a file name can be provided.
```php
$docparser->uploadDocumentByContents($parserId, $file, $remoteId = null, $filename = null);
```

**Fetch Document From An URL**

Imports a document from a publicly available HTTP(S) URL.
```php
$docparser->fetchDocumentFromURL($parserId, $url, $remoteId = null);
```

### [Parsed Data](https://dev.docparser.com/#parsed-data)

The Docparser API allows you to retrieve the extracted document data. You can either list the data of multiple documents or get the data of a specific document.

Both methods used for retrieving parsed data allow you to specify the "format" parameter - this allows you to choose between a flat structure and a nested array structure. For most implementations, leaving it as "object" will serve you fine.

> Please note: Polling the API for new results is not the recommended way of obtaining your data. A much better way than polling our API for parsed data is to use [Webhooks](https://docparser.com/integration/webhooks). By using webhooks, parsed data will be pushed to your API immediately after parsing.

**Get Data Of One Document**

Fetches the parsed data for a specific document by providing a `$parserId` and the `$documentId`. The `$documentId` is the Docparser Document ID which is returned when importing a document through the API.
```php
$docparser->getResultsByDocument($parserId, $documentId, $format = 'object');
```

**Get Data Of Multiple Documents**

Fetches the results of multiple documents parsed by a specific document parser. This function allows you granular filtering and ordering of the results. Please see our [documentation](https://dev.docparser.com/?shell#get-multiple-data-sets) for the list of available parameters.
```php
$docparser->getResultsByParser($parserId, $options = []);
```

## Contributing

Bug reports and pull requests are welcome on [GitHub](https://github.com/docparser/docparser-php).

Please follow [PSR-2](http://www.php-fig.org/psr/psr-2/) with your contributions and also take care of any changed / newly needed [phpDoc](https://phpdoc.org/) comments.

## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

### The MIT License (MIT)

*Copyright (c) 2016 DAUSINGER DIGITAL EURL.*

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Changelog
* 11/10/2017 initial release
