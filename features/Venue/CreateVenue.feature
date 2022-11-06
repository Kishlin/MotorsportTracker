Feature: It can create a Venue

  @backoffice
  Scenario: It saves a new venue
    Given the country "Netherlands" exists
    When a client creates the venue "Circuit Zandvoort" for the "Netherlands"
    Then the venue is saved

  Scenario: It cannot duplicate venues
    Given the venue "Zandvoort" exists
    When a client creates the venue "Circuit Zandvoort" for the "Netherlands"
    Then the venue creation is rejected

  Scenario: A venue needs an existing country
    Given the country "Netherlands" does not exist yet
    When a client creates the venue "Circuit Zandvoort" for the "Netherlands"
    Then the venue creation is rejected
