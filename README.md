### Description  
A useful tool for deciding what to cook next, what to buy and keeping track of what you already have
 * Digital recipe box
 * Pantry inventory monitoring
 * Recipe suggestions based on:
	* Available ingredients
	* Specific ingredient
	* Tag filtering
 * Shopping list generator 
 * Barcode reading
 * Receipt reading
 * Runs on PC, Android and iOS

### Notes to user
 * Project documented at https://kionokitse.wordpress.com/FridgeMate/

### What's next
 * Update blog content before it goes too far off
 * Format the README.md files for the tutorials 
 * Barcode reading tutorial with QuaggaJS
 * Try a shape detection API tutorial
 * Some way to check if a database exists else create it and add data
 * MySQL database backup
 * JSON generator for recipe info
 

### Folders
<details>
	<summary>Project folder structure</summary>
  
* Database
	* Sample data for the database
* Receipts
	* Images testing receipt reading
* Tutorials
	* MySQL: Complete
		* Simple tutorial for how to interact with MySQL database in JavaScript
		* .env file should be kept secret 
		* to build the project run "npm install"
	* QuaggaJS: In progress
		* Barcode scanning tutorial
</details>

### Progress log 
<details>
	<summary>Details of the project history</summary>
	
* 2020-11-11 Started the GitHub repository
* 2020-11-11 Working on sample database
* 2020-11-12 Looking for ways to read a barcode from a web app
* 2020-11-12 Adding more sample data
* 2020-11-15 Working on MySQL database tutorial
* 2020-11-16 Finished MySQL tutorial
* 2020-11-17 Writing content for blog 
</details>

### Notebook
<details>
	<summary>Research relating to barcode scanning</summary>
	
**Results**
* Dynamsoft’s JavaScript barcode scanner SDK ($$)
* QuaggaJS (free)
* ZXing (?)
* Shape Detection API (New partial support maybe not iOS)
	* chrome://flags -> Experimental Web Platform features -> enable

**Research**
	<details>
		<summary>Source links and comments </summary>

* [Scanning barcodes with built-in mobile camera and HTML5](https://a.kabachnik.info/reading-barcodes-with-built-in-camera-with-html5.html#fileapi)
		<details>
		
	* Pure JavaScript alternatives utilizing the library QuaggaJS
		* only library supporting live-detection of barcodes in the camera's live stream
	* HTML5 File API
		* The most simple way to scan a barcode with JavaScript works by capturing a picture with the HTML5 File API
		* mobile device will open a popup letting you choose, where to get the file from - including the camera. Selecting the latter will open the regular camera app. After the picture was taken, it will be given back to the JavaScript for further processing.
	* HTML5 getUserMedia API
		* Using the getUserMedia API a web application can gain access to the live stream of the built-in cameras. 
		* Using the getUserMedia API a web application can gain access to the live stream of the built-in cameras
		* Unfortunately, it is far not that well supported by browsers
		* possible to embed the live video stream of the camera into the web page at any place and even to control it: switch front and back camera, adjust the brightness, etc.
		* The main one is the lack of autofocus for video via getUserMedia
		* Without autofocus the barcode will always appear blurred because it is much closer
	* [Example and GitHub link](https://serratus.github.io/quaggaJS/examples/file_input.html)
	* [Another example](https://a.kabachnik.info/a-javascript-barcode-reader-with-bootstrap-3-and-quaggajs.html)
	* [How to Read Barcodes Online from a Web Application](https://medium.com/@beirikui1985/how-to-read-barcodes-online-from-a-web-application-6be5c7cec860)
	* Tutorial using Dynamsoft’s JavaScript barcode scanner SDK (costs money)
		</details>
		
* [How to create a live Barcode scanner using the webcam in JavaScript](https://ourcodeworld.com/articles/read/460/how-to-create-a-live-barcode-scanner-using-the-webcam-in-javascript)
	* Detailed description of how to use QuaggaJS
	* QuaggaJS is an extension of zxing
* [The Shape Detection API: a picture is worth a thousand words, faces, and barcodes](https://web.dev/shape-detection/)
	* Barcode detection has launched in Chrome 83 on certified devices with Google Play Services installed.
	* Shape Detection API currently supports the detection of faces, barcodes, and text.
	* Shopping apps can allow their users to scan EAN or UPC barcodes of items in a physical store to compare prices online.
	* Web applications can use text detection to translate texts such as, for example, restaurant menus.
* [Zxing Vs Google Vision](https://medium.com/@lkumar.sakare/zxing-vs-google-vision-fc3be8d83ace) 
	* Zxing library and google vision library in your project to scan the QR code and Barcode
	* ZXing 
		* “zebra crossing” is a barcode image processing library implemented in Java
		* The supported barcode formats include UPC-A, UPC-E, EAN-8, Code 93, Code 128, QR Code, Data Matrix, Aztec, PDF 417, etc.
		* Not good for multiple 1D barcodes
		* Zxing is not that much accurate than Google vision
	* Google vision library (maybe not web app)
		* 1D barcodes: EAN-13, EAN-8, Code-39, Code-93, Code-128, UPC-A, UPC-E, ITF, Codabar
		* 2D barcodes: PDF-417, AZTEC, QR Code, Data Matrix
		* detect multiple barcodes at once and work in any orientation
		* Google vision library is more faster, accurate and flexible than any other scanner library.
		* Google vision depends on native library downloaded post-install to perform scanning.
* [qrcode-react vs react-barcode vs react-qr-code vs react-qr-reader vs quagga vs qrcode-generator](https://www.npmtrends.com/qrcode-react-vs-react-barcode-vs-react-qr-code-vs-react-qr-reader-vs-quagga-vs-qrcode-generator)
	* Interesting comparison of which libraries are being used most
* [Looking for a barcode scanner](https://www.reddit.com/r/PHPhelp/comments/8vr7ac/looking_for_a_barcode_scanner/)
	* Quagga, Zxing, Scandit
* [Barcode Detection API](https://www.chromestatus.com/feature/4757990523535360)
	* Android WebView release 83
	* Chrome for Android release 83
	* Demos
*[Barcode detection using Shape Detection API](https://paul.kinlan.me/barcode-detection/)
	* Project maybe some code
* [The Shape Detection API: a picture is worth a thousand words, faces, and barcodes](https://web.dev/shape-detection/#barcodedetector)
	* Good site for how to use the API
* [Introduction to the Shape Detection API](https://blog.arnellebalane.com/introduction-to-the-shape-detection-api-e07425396861)
	* how to use the API
	* [Demo](https://shape-detection-api.arnelle.me/)
* [Using Shape Detection API in Chrome to Detect if anyone is Watching the Video](https://medium.com/@eyevinntechnology/using-shape-detection-api-in-chrome-to-detect-if-anyone-is-watching-the-video-f3f898d2912)
	*Another use case for the API
	</details>
	
</details>

	

	
	




			
		
		
		
		
		
		
		
### Next Blog Post

