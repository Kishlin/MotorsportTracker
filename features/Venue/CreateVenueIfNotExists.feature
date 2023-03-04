Feature: It can create a Venue

  Scenario: It returns the id of an existing venue
    Given the venue "Zandvoort" exists
    When a client creates the venue "Circuit Zandvoort" for the "Netherlands" if it does not exist with slug "circuit-zandvoort"
    Then the id of the existing venue "Zandvoort" is returned

  Scenario: It creates a missing venue
    Given the country "Netherlands" exists
    When a client creates the venue "Circuit Zandvoort" for the "Netherlands" if it does not exist with slug "circuit-zandvoort"
    Then the venue is saved
    Then the id of the freshly created venue "Zandvoort" is returned
