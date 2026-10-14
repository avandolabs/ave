# Avando AVE — Licence terms

**Version 1.0 · As of 1 September 2026**

> **Translation for information only.** The German version in `LICENSE.md`
> is legally binding.

Licensor: **Dipl.-Ing. (FH) Ulrich Braun (Avando Labs)** (hereinafter “Licensor”), address for service in the legal notice at **avandolabs.com/impressum**
Contact: **themes@avandolabs.com** · **avandolabs.com**

> **Summary** (not part of the contract, just a reading aid — in case of doubt the
> text of the sections prevails):
> There are two ways to use this theme. Whoever **pays** runs it on
> **one domain** and needs no attribution. Whoever **doesn't pay** may use it on
> **any number of** websites — but only if the website is
> **non-commercial**, **nobody is paid for building it** and the
> **attribution** stays in the footer.

---

## § 1 Subject matter

(1) The subject matter of this licence is the Contao theme **Avando AVE**
(Composer package `avando/ave`) including all stylesheets, templates, PHP
classes, configuration files, icons and documentation contained therein
(hereinafter “the Software”).

(2) The Design Studio (`avando/ave-editor`) is **not** covered by this
licence. This add-on package is governed by its own licence, which accompanies
it (see § 8).

(3) The Software is licensed, not sold. All rights not expressly granted here
remain with the Licensor.

---

## § 2 The two ways of use at a glance

|                                   | **Route A — Commercial licence** | **Route B — Free licence** |
|-----------------------------------|----------------------------------|----------------------------|
| Cost                              | one-off per domain               | none                       |
| Number of websites                | **one domain** per licence       | unlimited                  |
| Purpose of the website            | any, including commercial        | **non-commercial only**    |
| Paid creation of the website      | allowed                          | **not allowed**            |
| Attribution in the page footer    | not required                     | **required**               |
| Modifying the source code         | yes                              | yes                        |
| Passing on to third parties       | no                               | no                         |

Exactly one of the two routes must be chosen for each individual website.
Anyone who does not (or no longer) meets the requirements of Route B needs a
licence under Route A for that website.

---

## § 3 Route A — Commercial licence

(1) By purchasing a commercial licence, the Licensee obtains the simple
(non-exclusive), perpetual, non-transferable right to operate the Software
publicly on **one (1) domain** — for any purpose, including a commercial one.

(2) A **domain** within the meaning of this licence is a registered domain name
including all of its subdomains. `example.com`, `www.example.com` and
`shop.example.com` are therefore **one** domain and require **one** licence.
A different second-level domain — including a different country extension of
the same name (`example.de`) — is a further domain and requires a further
licence.

(3) Non-public installations of the same website are not counted: local
development environments, test and staging instances as well as backups,
provided they are not accessible to the public or indexed by search engines.

(4) The licence is tied to the domain, not to a person. It may be transferred
once to another domain if the website moves and the old domain no longer runs
the Software. Selling or leasing the licence to third parties is excluded.

(5) Agencies and service providers may set up and customise the Software on
behalf of their clients. A licence is required **per client domain**.

---

## § 4 Route B — Free licence for non-commercial websites

(1) The Software may be used **free of charge and on any number of domains**
if **all** of the following conditions are met:

  a) The website is **non-commercial** within the meaning of paragraph 2.
  b) **Nobody receives remuneration** within the meaning of paragraph 3 for the
     conception, design, setup, customisation, content entry, training or
     maintenance of this website.
  c) The **attribution** according to § 5 is present on the website and remains
     permanently visible.

(2) A website is **non-commercial** if it serves neither directly nor
indirectly to make a profit. This includes in particular websites of registered
associations, parishes, schools, nurseries, foundations, citizens' initiatives,
self-help groups and volunteer projects, as well as purely private websites. A
call for donations, a membership fee, an association magazine or a small sale
within the scope of the association's purpose does no harm, as long as the
operation of the website itself is not aimed at profit.

The following in particular are not non-commercial: websites of companies and
freelancers, online shops, booking and rental sites, advertising-financed
offerings, affiliate sites and websites that advertise a paid offering.

(3) **Remuneration** is any consideration with monetary value — fee, hourly
wage, flat rate, payment in kind, offsetting or barter. **As soon as anyone is
paid for building or running the website, Route B no longer applies**, even if
the website itself remains non-commercial. In this case a licence under § 3
must be purchased; who purchases it — service provider or client — is left to
the parties involved.

Expressly **not** remuneration are: volunteer work, the reimbursement of
documented expenses (hosting, domain, photo material) and the volunteer
allowance under Section 3 No. 26a of the German Income Tax Act (EStG).

(4) The Licensor may revoke the free use for the future if the requirements
cease to apply. Revocation is made in text form with a notice period of 30
days; within this period a licence under § 3 may be purchased.

---

## § 5 Attribution (Route B only)

(1) The Software outputs the notice itself. It appears at the end of every page
and reads:

> Built with the free [Avando AVE theme for Contao](https://avandolabs.com)

The notice is generated by the Software's page layout
(`bundle/contao/templates/twig/page/layout.html.twig`, block `.ave-credit`) and
styled by its stylesheet. There is **nothing to add** — there is only nothing
to remove.

(2) Any measure that makes the notice invisible or illegible is prohibited, in
particular:

  - removing or commenting out the block in the template or in an overridden
    version of the template,
  - `display:none`, `visibility:hidden`, zero height or opacity, moving it out
    of the visible area, covering it with other elements,
  - reducing the font size or colour contrast below what the Software
    delivers,
  - removing the link or redirecting it to another address,
  - suppressing the notice via JavaScript after the page has loaded.

(3) Adjustments that do **not** impair legibility are permitted: a translation
of the sentence into the language of the website, a surface or font adapted to
the page design, a different position within the page footer.

(4) The link carries `rel=“nofollow“`. This is intentional and not an
oversight: an attribution line that appears on thousands of websites at the
same time is exactly the pattern search engines assess as a link scheme — the
markup therefore protects both sides. It must not be removed.

(5) Anyone who has purchased a licence under § 3 may remove the notice, but
does not have to. This is **not** done by changing the template, but via the
Pro token included with the delivery. It is entered in the back end in the
website root of the licensed domain (field “Pro token” in the section “Avando
AVE”); alternatively in the configuration of the installation
(`avando_ave.license` in `config/config.yaml`).

This way the decision survives every update, and it remains traceable when it
was made. A modified template falls back at the next update; a page layout
overridden in the project also freezes all future improvements to it.

(6) The Pro token is part of the licence and must be treated like an access
key. Passing it on to third parties, publishing it or using it on a domain
without its own licence under § 3 is a breach of § 7 and terminates the
licence under § 13.

---

## § 6 Permitted on both routes

(1) Installing, operating and backing up the Software on own or rented
infrastructure.

(2) **Modifying the source code** for one's own website: own stylesheets,
overridden templates, changed tokens, added classes. The Licensor owes no
support for customised installations, and updates may overwrite
customisations.

(3) Making any number of backup copies.

(4) Passing it on to a commissioned service provider **for the purpose of
working on the licensed website**. The service provider does not acquire any
right of use of their own from this.

---

## § 7 Prohibited on both routes

(1) Passing on, publishing, selling, renting, lending or sublicensing the
Software or parts of it to third parties — including in modified or renamed
form or as part of one's own product, theme, template, website builder or
template collection.

(2) Making the source code publicly available, for example in a public
repository, a package registry or a download area.

(3) Use in an offering that makes the Software available to third parties as
part of a website builder, site generator or multi-tenant system, unless a
licence under § 3 exists for every domain operated in this way.

(4) Removing, altering or obscuring copyright, trademark or licence notices in
the source code.

(5) Circumventing or defeating technical measures for licence verification.

(6) Using the Software or its components to train machine learning models
that generate source code or designs.

---

## § 8 Design Studio (Pro add-on)

The Design Studio (`avando/ave-editor`) is a paid add-on and **not part of
Route B**. The theme is fully functional without the Studio — all design
values are available as CSS tokens in the stylesheets and can be overridden by
hand. The Studio is governed by the licence in `ave-editor/LICENSE.md`.

---

## § 9 Third-party components

The Software contains and loads third-party components that are subject to
their own licences. For the respective components, these licences take
precedence over this licence. The overview is in `THIRD-PARTY.md`.

In particular: **fonts** are not included, but loaded from Google Fonts when
needed and stored locally. They are subject to the SIL Open Font License or the
Apache License; the licence file is stored next to the font file on download
and must remain there.

**Demo content** (sample texts, sample images, sample PDFs under `files/demo/`)
is not part of the licensed Software. It serves demonstration purposes and must
not be taken over into a published website.

---

## § 10 Updates and support

(1) There is no entitlement to updates, new features or support unless
expressly agreed otherwise.

(2) If updates are provided, these licence terms apply to them in the version
enclosed in each case.

---

## § 11 Warranty

(1) The Software is provided in its respective state. The Licensor does not
warrant that it is fit for a particular purpose, uninterrupted or free of
errors.

(2) For Route B (provision free of charge), the Licensor is liable for
material defects and defects of title only in the case of fraudulent
concealment.

(3) The Licensee remains responsible for the lawful operation of their website
— in particular for the legal notice, privacy policy, consents, accessibility
and image rights. Notes in the Software or its documentation on these topics
are assistance, not legal advice and not a warranted characteristic.

---

## § 12 Liability

(1) The Licensor is liable without limitation for intent and gross negligence,
for injury to life, body or health and under the German Product Liability Act.

(2) In the event of a slightly negligent breach of an essential contractual
obligation, liability is limited to the foreseeable damage typical of the
contract, at most to the licence fee paid for the domain concerned. Otherwise
liability is excluded.

(3) The Licensee backs up their data themselves. The Licensor is liable for the
loss of data only to the extent that would have arisen had the data been
backed up properly.

---

## § 13 Term and termination

(1) The licence is valid for an unlimited period.

(2) It terminates automatically in the event of a breach of § 5 or § 7. The
Software must then be removed from all affected systems.

(3) A breach that is fully remedied within 14 days of a request in text form
leaves the licence unaffected. This option exists once.

---

## § 14 Changes to this licence

The Licensor may issue future versions of this licence. For a licence already
purchased, the version enclosed at the time of purchase applies.

---

## § 15 Final provisions

(1) The law of the Federal Republic of Germany applies, excluding the UN
Convention on Contracts for the International Sale of Goods (CISG).

(2) The place of jurisdiction is **Hainichen**, provided the Licensee is a
merchant, a legal entity under public law or a special fund under public law.

(3) Should a provision be invalid, the validity of the remaining provisions
shall not be affected. The invalid provision shall be replaced by what comes
closest to its economic purpose.

(4) Amendments and additions require text form.

---

## Contact

Questions about the licence, ordering a commercial licence, enquiries about
multiple or agency licences: **themes@avandolabs.com**

---

*Copyright © 2026 Ulrich Braun. All rights reserved.*
