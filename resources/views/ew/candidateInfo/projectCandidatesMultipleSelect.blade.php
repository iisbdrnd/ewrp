<select id="candidate_id" name="candidate_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks" multiple placeholder="All Candidate">
    <option value=""></option>
    @foreach($projectCandidates as $projectCandidate)
    <option value="{{$projectCandidate->id}}">{{$projectCandidate->candidate_id.' - '.$projectCandidate->candidate_name}}</option>
    @endforeach
</select>
