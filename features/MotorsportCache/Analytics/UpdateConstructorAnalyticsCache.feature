Feature: It updates the cache for constructor analytics

  Scenario: It updates the cache for available analytics
    Given the analytics data for Constructor "Red Bull Honda In Formula One 2023" exist
    And the analytics data for Constructor "Mercedes In Formula One 2023" exist
    When the constructor analytics cache is updated for season "Formula One" "2023"
    Then the cache has constructor analytics for "Formula One" 2023
    And the cache for constructor analytics for "Formula One" 2023, at index 0, is for "Red Bull Honda"
    And the cache for constructor analytics for "Formula One" 2023, at index 1, is for "Mercedes"
