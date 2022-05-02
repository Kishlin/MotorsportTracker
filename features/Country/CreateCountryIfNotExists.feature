Feature: It can create missing Countries

  Scenario: It saves a new country
    When a client searches a country which does not exist
    Then the new country is saved

  Scenario: It does not recreate an existing country
    Given a country exists
    When a client searches for the existing country
    Then the country was not recreated
