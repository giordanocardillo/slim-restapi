# Documentazione RESTful API

Una RESTful API basata su Slim Framework con programmazione exception driven.

## Struttura directory:

```
+-- app               Contiene i files dell'API
|  +-- autoloaders    Contiene le classi di autoloading
|  +-- lib            Contiene le librerie personali
|  |  +-- Exceptions  Contiene le eccezioni utilizzate dall'API
|  +-- routes         Contiene le classi di routing
|  +-- utils          Contiene classi di utility (Inclusa connessione al DB)
|  +-- vendor         Contiene classi di terze parti
|  +-- views          Contiene le classi di rendering della libreria
+-- public_html       Parte web pubblica
|  +-- images         Cartella immagini
|  +-- index.php      Pagina principale dell'API
```

## Autenticazione

L'API utilizza un meccanismo di autenticazione basato su username e password memorizzate in database.

L'algoritmo di cifratura utilizzato è quello fornito da PHP >= 5.5, ovvero bcrypt eseguito con complessità 10
(per ragioni di tempi di risposta).


## Oggetti Response

Tutte le risposte della API sono oggetti di tipo Response. Si può avere una `SuccessResponse` o una `ErrorResponse`.

La `SuccessResponse` è sempre accompagnata da un codice di stato `HTTP 200 OK` e ha un campo `data` in cui è presente
il risultato dell'operazione effettuata.

Esempio di `SuccessResponse`:

```
{"status": "success", data":"Operazione eseguita"}
```

Se la chiamata REST è una procedura che non fornisce alcun dato di ritorno, l'oggetto `SuccessResponse` sarà del tipo:

```
{"status": "success"}
```

Una `ErrorResponse`, invece, è sempre accompagnato da un codice di stato `HTTP` di errore tra i seguenti:

```
HTTP 400 Bad Request
HTTP 401 Unauthorized
HTTP 403 Forbidden
HTTP 404 Not Found
HTTP 500 Internal server error
```

Inoltre, l'oggetto che viene ritornato in caso d'errore non contiene il campo `data` ma è strutturato come segue:

Esempio di `ErrorResponse`:

```
{"status: "error", "error": {"message": "Eccezione", "errorClass": "Exception", "trace": "<STACK TRACE>"}}
```

Il campo `trace` è presente soltanto negli errori con codice di risposta `HTTP 500`


## Sessione

La sessione è gestita con meccanismo JWT (Json Web Token) Open Standard RFC7519 (http://jwt.io/).

Il gestore della sessione è la classe SessionManager presente nella cartella utils. La durata della sessione è configurabile dal suddetto file.

Tutte le chiamate, ad eccezione della chiamata al login, check sessione e registrazione utente, devono contenere un header di autenticazione come specificato dallo standard.

Tale header, posto nel campo "Authentication" deve essere strutturato come di seguito:

```
Authentication "Bearer <TOKEN>"
```

## RESTful API calls

Le chiamate da effettuare alla REST API sono le seguenti. Vengono riportati soltanto gli esiti positivi perché in caso di esito negativo il valore di ritorno è sempre un oggetto `ErrorResponse`.

Nell'elenco delle eccezioni sono riportate solo quelle specifiche alla chiamata, evitando le eccezioni di tipo tecnico.

I parametri opzionali vengono indicati tra parentesi quadre: `[PARAMETRO]`.

I segnaposto sono indicati tra parentesi angolari: `<SEGNAPOSTO>`.

Per parametri che richiedono valori specifici, il pool delle possibilità è indicato tra parentesi tonde: `(VAL1, VAL2, ..., VALN)`.

#### Sessione - `session.php`

* Controllo validità sessione

		GET /session/check

	* Risposta:

			{"data":{"session":"valid","expiresIn":"<SECONDI AL TERMINE>"}}

	* Eccezioni:

		* `SignatureInvalidException` - verifica firma non riuscita
		* `ExpiredException` - sessione scaduta
