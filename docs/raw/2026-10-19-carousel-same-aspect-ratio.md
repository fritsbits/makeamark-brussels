# Homepage carousel aspect-ratio selection

- Date: 2026-10-19
- User instruction: “we should only use photos in same aspect ratio”
- Decision: Keep the existing 4:3 group image first. Show the carousel in a
  consistent 4:3 frame, cropping the 3:2 landscape photos to fit. Exclude the
  2:3 portrait serving-food photo because fitting it to 4:3 would crop away too
  much of the original scene.
- Implementation: Seven supplied landscape photos follow the group image in
  the carousel. The supplied portrait remains catalogued but unused there.
