Feature: It can view results for an event

  @api
  Scenario: It views existing results for an event
    Given the results for the "First Two At Formula One Bahrain GP 2023" exist
    When a client views results for the "First Two At Formula One Bahrain GP 2023"
    Then the results for the "First Two At Formula One Bahrain GP 2023" are returned

  @api
  Scenario: It views missing results
    Given the "results" "First Two At Formula One Bahrain GP 2023" does not exist yet
    When a client views results for the "First Two At Formula One Bahrain GP 2023"
    Then it views no results for any race
