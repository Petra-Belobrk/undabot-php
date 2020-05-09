

## Undabot PHP Zadatak

Laravel aplikacija pomocu koje se pretrazuju github issues-i
Ovisno o ucestalosti trazenog pojma, racuna se popularnost


## Instalacija

- u .env fileu unijeti podatke o bazi
- composer install
- npm install
- php artisan migrate

## Nacin rada

- unutar Search polja unese se rijec koja bi se pretrazivala
- na dropdownu se odabire odakle se zeli pretrazivati (za sada je podrzan samo github, no moze se u buducnosti to promjeniti)
- ukoliko se ne klikne checkbox rezultat je u slijedecem obliku:
       
        POST http://undabot.test/search/<term>
        Body response:
        {
            term: <term> : string
            score: <score> : float
        }
        
        primjer:
        
       {
       "term":"php",
       "score":0.33
       }
       
- klikom na checkbox dobiva se slijedeci oblik:

        POST http://undabot.test/search/<term>
        Body response:
        {
           'links': 
                  'self': "http://www.undabot.test/search/<term>",
           'data':  
                   'type': <type> : string,
                   'id': <id> : int,
                   'attributes': 
                       'title': <term> :string,
                       'score': <score> : float,
                       'positive_terms': <rocks> : int,
                       'negative_terms': <sucks> : int,
                       'total_number': <total> : int,
                   
                   'relationships': 
                       'provider': <provider_name> : string,
                       'url': <provider_url> : string,                    
        }
        
        primjer:
        
        {
        "links":
            {
            "self":"http:\/\/www.undabot.test\/search\/php"},
        "data":
            {
            "type":"keyword",
            "id":13,
            "attributes":
                {
                "title":"php",
                "score":0.33,
                "positive_terms":2504,
                "negative_terms":5045,
                "total_number":7549
                },
            "relationships":
                {
                    "provider":"GitHub",
                    "url":"https:\/\/api.github.com\/search\/issues"
                }
            }
        }

