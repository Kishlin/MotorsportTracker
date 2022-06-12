Feature: It can create a Venue

  Scenario: It saves a new venue
    Given a country exists
    When a client creates a new venue for the country
    Then the new venue is saved

  Scenario: It cannot duplicate venues
    Given a venue exists for the country
    When a client creates a new venue for the country
    Then the venue creation is rejected

  Scenario: A venue needs an existing country
    When a client creates a venue for a missing country
    Then the venue creation is rejected