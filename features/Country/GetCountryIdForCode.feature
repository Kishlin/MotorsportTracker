Feature: It can get the country id from a code

  Scenario: It retrieves an existing country
    Given a country exists
    When a client searches the country's id
    Then the country's id is returned

  Scenario: It fails to retrieve a non-existing country
    When a client searches for a non-existing country
    Then the query is rejected
