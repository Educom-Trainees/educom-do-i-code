"use client";

import { Gantt, Task, EventOption, StylingOption, ViewMode, DisplayOption } from 'gantt-task-react';
import "gantt-task-react/dist/index.css";
import Link from "next/link";

function issueParser(data: any[]) {

  let tasks: Task[] = [];

  for (const issue of data) {
    let start = labelParser(issue["labels"]);
    let closed = false;
    if (issue["state"] === "closed") { closed = true };

    if (start != null) {
      const task: Task = {
        name: issue["title"],
        start: start,
        end: issue["closed_at"] ? new Date(issue["closed_at"]) : dateParser(start, 5),
        id: "Task " + issue["id"],
        type: "task",
        progress: closed ? 100 : 0,
        isDisabled: true,
        styles: { backgroundColor: "#ff4740", progressColor: '#0B4A69'},
      }

      if (task.start < task.end) {
        tasks.push(task);
      }
    }
  }
  console.log(tasks);
  return tasks;
}

function labelParser(data: any) {
  let names = [];

  for (const label of data as any) {
    if (label["name"].startsWith("week-")) {
      names.push(label["name"].replace(/^\D+/g, ''));
    }
  }

  if (names.length === 0) {
    return null;
  } else {
    let week = names[names.length - 1];
    return weekParser(week);
  }
}

function weekParser(week: number) {
  let year = new Date().getFullYear();

  if (week < 1 || week > 53) {
    throw new RangeError("ISO 8601 weeks are numbered from 1 to 53");
  }

  const simple = new Date(year, 0, 1 + (week - 1) * 7);
  const dayOfWeek = simple.getDay();
  const isoWeekStart = simple;

  // get the monday past, and add a week if the day was friday, saturday or sunday.
  isoWeekStart.setDate(simple.getDate() - dayOfWeek + 1);
  if (dayOfWeek > 4) {
    isoWeekStart.setDate(isoWeekStart.getDate() + 7);
  }

  // the latest possible iso week starts on december 28 of the current year
  if (isoWeekStart.getFullYear() > year ||
    (isoWeekStart.getFullYear() == year &&
      isoWeekStart.getMonth() == 11 &&
      isoWeekStart.getDate() > 28)) {
    throw new RangeError(`${year} has no ISO week ${week}`);
  }

  return isoWeekStart;
}

function dateParser(date: Date, days: number) {
  let result = new Date(date);
  result.setDate(result.getDate() + days);
  return result;
}

const errorText =
  <>
    <p>Het lijkt erop dat deze repository niet goed is ingesteld. Bij het instellen van je repository, let op het volgende.</p>
    <ul>
      <li>Zorg dat je repository gebruikt maakt van <Link href="https://docs.github.com/en/issues">Issues</Link>.</li>
      <li>Maak labels aan voor iedere week van het jaar. Gebruik hiervoor het formaat &quot;week-x&quot;, waarbij &quot;x&quot; het weeknummer is.</li>
      <li>Issues die op &quot;closed&quot; zijn gezet vóór de geplande week van het huidige jaar worden niet opgenomen.</li>
      <li>Als je pas je repository goed hebt ingesteld, kan het nog even duren voordat deze data beschikbaar komt.</li>
    </ul>
    <p>Heb je je repository goed ingesteld, maar krijg je nog steeds deze melding? Neem met ons contact op via <Link href="mailto:&#099;&#111;&#110;&#116;&#097;&#099;&#116;&#064;&#100;&#111;&#045;&#105;&#045;&#099;&#111;&#100;&#101;&#046;&#099;&#111;&#109;">&#099;&#111;&#110;&#116;&#097;&#099;&#116;&#064;&#100;&#111;&#045;&#105;&#045;&#099;&#111;&#100;&#101;&#046;&#099;&#111;&#109;</Link>.</p>
  </>

export default function Chart({ data }: { data: any }) {
  let array = issueParser(data);

  if (array.length === 0) {
    return (errorText)
  }

  // https://github.com/MaTeMaTuK/gantt-task-react/issues/218
  // https://github.com/MaTeMaTuK/gantt-task-react/issues/216
  return <Gantt tasks={issueParser(data)} locale="nl" todayColor="rgba(115, 0, 89, 0.15)"/>;
}
