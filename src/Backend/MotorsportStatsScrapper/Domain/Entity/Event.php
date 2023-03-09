<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity;

final class Event
{
    private const QUALIFYING         = 'Qualifying';
    private const QUALIFYING_1       = 'Qualifying 1';
    private const FIRST_QUALIFYING   = '1st Qualifying';
    private const QUALIFYING_GROUP_A = 'Qualifying Group A';

    /**
     * @param array<string, Session> $sessions
     */
    private function __construct(
        private readonly string $slug,
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly ?string $status,
        private readonly ?int $startTimeUtc,
        private readonly ?int $endTimeUtc,
        private readonly string $venueName,
        private readonly string $venueSlug,
        private readonly string $countryName,
        private readonly string $countryPicture,
        private array $sessions,
    ) {
    }

    public function mergeQualifyingSessions(): void
    {
        if (array_key_exists(self::QUALIFYING_1, $this->sessions)) {
            $this->createQualifyingSessionFrom(self::QUALIFYING_1);
        } elseif (array_key_exists(self::FIRST_QUALIFYING, $this->sessions)) {
            $this->createQualifyingSessionFrom(self::FIRST_QUALIFYING);
        } elseif (array_key_exists(self::QUALIFYING_GROUP_A, $this->sessions)) {
            $this->createQualifyingSessionFrom(self::QUALIFYING_GROUP_A);
        }

        foreach (array_keys($this->sessions) as $sessionKey) {
            if (str_starts_with($sessionKey, 'Qualifying ') || str_ends_with($sessionKey, ' Qualifying') || 'Super Pole' === $sessionKey) {
                unset($this->sessions[$sessionKey]);
            }
        }
    }

    public function removeSessionsCancelledOrPostponed(): void
    {
        $filteredSessions = array_filter(
            $this->sessions,
            static function (Session $session) {
                return 'Cancelled' !== $session->status() && 'Postponed' !== $session->status();
            },
        );

        $this->sessions = $filteredSessions;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }

    public function status(): ?string
    {
        return $this->status;
    }

    public function startTimeUtc(): ?int
    {
        return $this->startTimeUtc;
    }

    public function endTimeUtc(): ?int
    {
        return $this->endTimeUtc;
    }

    public function venueName(): string
    {
        return $this->venueName;
    }

    public function venueSlug(): string
    {
        return $this->venueSlug;
    }

    public function countryName(): string
    {
        return $this->countryName;
    }

    public function countryPicture(): string
    {
        return $this->countryPicture;
    }

    /**
     * @return Session[]
     */
    public function sessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param array{
     *     slug: string,
     *     name: string,
     *     shortName: null|string,
     *     status: null|string,
     *     startTimeUtc: null|int,
     *     endTimeUtc: null|int,
     *     venue: array{
     *         name: string,
     *         slug: string,
     *     },
     *     country: array{
     *         name: string,
     *         slug: string,
     *         picture: string,
     *     },
     *     sessions: array{
     *         session: array{
     *             name: string,
     *             shortName: null|string,
     *             slug: string,
     *             code: null|string,
     *         },
     *         status: null|string,
     *         hasResults: bool,
     *         startTimeUtc: null|int,
     *         endTimeUtc: null|int,
     *     }[],
     * } $data
     */
    public static function fromData(array $data): self
    {
        $sessions = [];
        foreach ($data['sessions'] as $session) {
            $sessions[$session['session']['name']] = Session::fromData($session);
        }

        return new self(
            $data['slug'],
            $data['name'],
            $data['shortName'],
            $data['status'],
            $data['startTimeUtc'],
            $data['endTimeUtc'],
            $data['venue']['name'],
            $data['venue']['slug'],
            $data['country']['name'],
            $data['country']['picture'],
            $sessions,
        );
    }

    private function createQualifyingSessionFrom(string $keyFrom): void
    {
        $this->sessions[self::QUALIFYING] = Session::fromData([
            'session' => [
                'name'      => self::QUALIFYING,
                'shortName' => null,
                'code'      => null,
                'slug'      => substr($this->sessions[$keyFrom]->slug(), 0, -2),
            ],
            'status'       => $this->sessions[$keyFrom]->status(),
            'hasResults'   => $this->sessions[$keyFrom]->hasResults(),
            'startTimeUtc' => $this->sessions[$keyFrom]->startTimeUtc(),
            'endTimeUtc'   => $this->sessions[$keyFrom]->endTimeUtc(),
        ]);
    }
}
