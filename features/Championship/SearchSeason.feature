Feature: It can search a season

  Scenario: It searches an existing season
    Given the season "Formula One 2022" exists
    When a client searches seasons with keyword "formula" and year "2022"
    Then the id of the season "Formula One 2022" is returned

  Scenario: It searches a season that does not exist
    Given the "season" "FormulaOne 2022" does not exist yet
    When a client searches seasons with keyword "formula" and year "2022"
    Then it does not receive any season id
