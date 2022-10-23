Feature: It can create missing Countries

  Scenario: It creates a searched missing country
    When a client searches for the country with code "nl"
    Then the country is saved

  Scenario: It does not recreate an existing country
    Given the country Netherlands exists
    When a client searches for the country with code "nl"
    Then the country is not recreated
