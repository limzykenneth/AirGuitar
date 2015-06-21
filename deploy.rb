require 'sinatra'
require 'json'
require 'logger'

$LOG = Logger.new('git-deploy.log',0,100*1024*1024)

post '/payload', :agent => /GitHub-Hookshot/ do
  request.body.rewind
  payload_body = request.body.read
  verify_signature(payload_body)
  commands = ['git status','git pull origin master','git submodule sync','git submodule update','git submodule status']
  commands.each do |i|
  	system i
  end
  
  $LOG.info "Git deploy ran without critical error."
  return "Git deploy ran without critical error."
end

def verify_signature(payload_body)
  signature = 'sha1=' + OpenSSL::HMAC.hexdigest(OpenSSL::Digest.new('sha1'), ENV['GIT_TOKEN'], payload_body)
  return halt 500, "Signatures didn't match!" unless Rack::Utils.secure_compare(signature, request.env['HTTP_X_HUB_SIGNATURE'])
end