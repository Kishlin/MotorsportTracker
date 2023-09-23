Feature: Scrap seasons from an external data source

  Scenario: It scrapes events from an external data source
    Given the series exists
      | name                               | short_name | short_code | ref                                  |
      | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    And the seasons for "FIA Formula One World Championship" exist
      | year | ref                                  |
      | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
    And the external data source has the calendar for "FIA Formula One World Championship" "2023"
"""
{
    "events": [
        {
            "uuid": "263a6903-5153-4d8f-b28e-82b545150bf5",
            "name": "Bahrain Grand Prix",
            "shortName": "Bahrain GP",
            "shortCode": "BHR",
            "status": "",
            "startTimeUtc": 1677790800,
            "endTimeUtc": 1678050000,
            "venue": {
                "name": "Bahrain International Circuit",
                "uuid": "2ec7adab-816d-4b4b-b700-5000bd81f692"
            },
            "country": {
                "name": "Bahrain",
                "uuid": "89019070-bc64-468e-b9df-1d39058d871e",
                "picture": "https://assets.motorsportstats.com/flags/svg/bh.svg"
            },
            "sessions": [
                {
                    "uuid": "a741f9fa-c13f-48cf-8f4f-5b2dbb5fb844",
                    "name": "Race",
                    "hasResults": true,
                    "startTimeUtc": 1678028400,
                    "endTimeUtc": null
                }
            ]
        }
    ]
}
"""
    When I scrap the calendar for "FIA Formula One World Championship" "2023"
    Then the external data source is called "1" times
    And the calendar for "FIA Formula One World Championship" "2023" from the external source is cached to minimize future calls
    And it saved "1" new "event_session"
    And it saved "1" new "session_type"
    And it saved "1" new "country"
    And it saved "1" new "venue"
    And it saved "1" new "event"
    And it saved the event "Bahrain Grand Prix" for "FIA Formula One World Championship" "2023"
